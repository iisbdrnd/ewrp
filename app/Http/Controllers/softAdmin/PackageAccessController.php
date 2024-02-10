<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use Helper;
use App\Model\SoftwarePackage;
use App\Model\SoftwarePackageAccess;
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\SoftwareMenuAccess_admin;
use App\Model\SoftwareInternalLinkAccess_admin;
use App\Model\CrmPackage;
use App\Model\CrmPackageAccess;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class PackageAccessController extends Controller
{
    public function packageAccess(Request $request)
    {
        $package_id = $request->input('data');
        $data['package_info'] = CrmPackage::valid()->find($package_id);
        $data['software_modules'] = SoftwareModules::active()->orderBy('sl_no', 'asc')->get()->chunk(4);

        return view('softAdmin.softwarePackage.packageAccess', $data);
    }

    public function packageAccessMenuView(Request $request)
    {
        $module_id = $request->module_id;
        $package_id = $request->package_id;
        $data['software_module'] = SoftwareModules::active()->find($module_id);
        $checkAll = true;

        if(!empty($data['software_module'])) {
            $software_menus = SoftwareMenu::select('software_menu.*', 'crm_package_access.id as menu_access')
                ->leftJoin('crm_package_access', function($join) use ($package_id) {
                    $join->on('software_menu.id', '=', 'crm_package_access.menu_id')
                        ->on('crm_package_access.package_id', '=', DB::raw($package_id))
                        ->on('crm_package_access.type', '=', DB::raw(1))
                        ->on('crm_package_access.valid', '=', DB::raw(1));
                })
                ->where('software_menu.module_id', $module_id)
                ->where('software_menu.status', 1)
                ->orderBy('software_menu.sl_no', 'asc')
                ->get();

            foreach($software_menus as $software_menu) {
                $software_menu->internal_links = SoftwareInternalLink::active()
                    ->select('software_internal_link.*', 'crm_package_access.id as link_access')
                    ->leftJoin('crm_package_access', function($join) use ($package_id) {
                        $join->on('software_internal_link.id', '=', 'crm_package_access.link_id')
                            ->on('crm_package_access.package_id', '=', DB::raw($package_id))
                            ->on('crm_package_access.type', '=', DB::raw(2))
                            ->on('crm_package_access.valid', '=', DB::raw(1));
                    })
                    ->where('software_internal_link.menu_id', $software_menu->id)
                    ->orderBy('id', 'asc')
                    ->get();

                if($checkAll) { if(empty($software_menu->menu_access)) { $checkAll = false; } }
                foreach($software_menu->internal_links as $internal_links) {
                    if($checkAll) { if(empty($internal_links->link_access)) { $checkAll = false; } }
                }
            }

            $data['software_menus'] = $software_menus;
            $data['checkAll'] = $checkAll;

            return view('softAdmin.softwarePackage.packageAccessMenuView', $data);
        }
    }

    public function packageAccessAction(Request $request)
    {
        DB::beginTransaction();
        $package_id = $request->input('package');
        $module_id = $request->input('module');
        $menu = $request->input('menu');
        $link = $request->input('internal_link');

        $menu_db = collect(SoftwareMenu::packageAccessMenus($package_id, $module_id)->pluck('id'));
        $link_db = collect(CrmPackageAccess::join('software_internal_link', 'crm_package_access.link_id', '=', 'software_internal_link.id')
            ->join('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
            ->select('crm_package_access.*')
            ->where('software_menu.module_id', $module_id)
            ->where('crm_package_access.package_id', $package_id)
            ->where('crm_package_access.type', 2)
            ->where('software_menu.valid', 1)
            ->where('software_internal_link.valid', 1)
            ->where('crm_package_access.valid', 1)
            ->get()->pluck('link_id'));

        $menu_diff = $menu_db->diff($menu);
        $link_diff = $link_db->diff($link);

        if(!empty($menu_diff)) {
            //Remove Package Access
            $cur_menu_ac = CrmPackageAccess::valid()->whereIn('menu_id', $menu_diff)->where('type', 1)->where('package_id', $package_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                CrmPackageAccess::valid()->find($cur_menu->id)->delete();
            }
        }
        if(!empty($link_diff)) {
            //Remove Package Access
            $cur_link_ac = CrmPackageAccess::valid()->whereIn('link_id', $link_diff)->where('type', 2)->where('package_id', $package_id)->get();
            foreach($cur_link_ac as $cur_link) {
                CrmPackageAccess::valid()->find($cur_link->id)->delete();
            }
        }

        if(!empty($menu)) {
            foreach($menu as $menu_id) {
                if(!$menu_db->contains($menu_id)) {
                    CrmPackageAccess::create(array(
                        'menu_id' => $menu_id,
                        'type' => 1,
                        'package_id' => $package_id
                    ));
                }
            }
        }

        if(!empty($link)) {
            foreach($link as $link_id) {
                if(!$link_db->contains($link_id)) {
                    CrmPackageAccess::create(array(
                        'link_id' => $link_id,
                        'type' => 2,
                        'package_id' => $package_id
                    ));
                }
            }
        }
        DB::commit();
    }
    
    public function packageAccessView(Request $request)
    {
        $package_id = $request->package_id;
        $module_id = $request->module_id;
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'link_name', 'module_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $packageAccess = CrmPackageAccess::leftJoin('software_menu as a', 'crm_package_access.menu_id', '=', 'a.id')
            ->leftJoin('software_internal_link', 'crm_package_access.link_id', '=', 'software_internal_link.id')
            ->leftJoin('software_menu as b', 'software_internal_link.menu_id', '=', 'b.id')
            ->leftJoin('software_modules as c', 'a.module_id', '=', 'c.id')
            ->leftJoin('software_modules as d', 'b.module_id', '=', 'd.id')
            ->select('crm_package_access.*', DB::raw("if(crm_package_access.link_id=0, a.menu_name, b.menu_name) as menu_name"), 'software_internal_link.link_name', DB::raw("if(crm_package_access.link_id=0, c.module_name, d.module_name) as module_name"))
            ->where('crm_package_access.package_id', $package_id)
            ->where(function($query) use ($search)
            {
                $query->where('a.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('b.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_internal_link.link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('c.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('d.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('crm_package_access.updated_at', 'LIKE', '%'.$search.'%');
            });
        if(!empty($module_id)) {
            $packageAccess = $packageAccess->where(function($query) use ($module_id)
            {
                $query->where('a.module_id', $module_id)
                    ->orWhere('b.module_id', $module_id);
            });
        }
        $data['packageAccess'] = $packageAccess->orderBy($ascDesc[0], $ascDesc[1])->paginate($paginate->perPage);
        return view('softAdmin.softwarePackage.packageAccessView', $data);
    }


}
