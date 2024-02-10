<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use Helper;
use App\Model\EnProviderUser_provider;
use App\Model\SoftwareModules_provider;
use App\Model\SoftwareMenu_provider;
use App\Model\SoftwareInternalLink_provider;
use App\Model\ProjectAccess_provider;
use App\Model\SoftwareMenuAccess_provider;
use App\Model\SoftwareInternalLinkAccess_provider;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;

class EmployeeAccessController  extends Controller
{
    public function employeeAccess(Request $request)
    {
        $emp_id = $request->input('data');
        $data['emp_info'] = EnProviderUser_provider::valid()->find($emp_id);
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['software_modules'] = collect(SoftwareModules_provider::projectAccessModules($project_id))->chunk(4);
        // dd($data['emp_info']);
        return view('provider.admin.providerUsers.employeeAccess', $data);
    }

    public function employeeAccessMenuView(Request $request)
    {
        
        $module_id = $request->module_id;
        $user_id = $request->user_id;
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['software_module'] = SoftwareModules_provider::active()->find($module_id);
        $checkAll = true;
        
        if(!empty($data['software_module'])) {
            $software_menus = SoftwareMenu_provider::join('project_access', function($join) use ($project_id) {
                $join->on('software_menu.id', '=', 'project_access.menu_id')
                    ->on('project_access.project_id', '=', DB::raw($project_id))
                    ->on('project_access.type', '=', DB::raw(1))
                    ->on('project_access.valid', '=', DB::raw(1));
                })
                ->leftJoin('software_menu_access', function($join) use ($user_id) {
                    $join->on('software_menu.id', '=', 'software_menu_access.menu_id')
                        ->on('software_menu_access.user_id', '=', DB::raw($user_id))
                        ->on('software_menu_access.valid', '=', DB::raw(1));
                })
                ->select('software_menu.*', 'software_menu_access.id as menu_access')
                ->where('software_menu.module_id', $module_id)
                ->where('software_menu.status', 1)
                ->where('software_menu.valid', 1)
                ->orderBy('software_menu.sl_no', 'asc')
                ->get();

            foreach($software_menus as $software_menu) {
                $software_menu->internal_links = SoftwareInternalLink_provider::join('project_access', function($join) use ($project_id) {
                    $join->on('software_internal_link.id', '=', 'project_access.link_id')
                        ->on('project_access.project_id', '=', DB::raw($project_id))
                        ->on('project_access.type', '=', DB::raw(2))
                        ->on('project_access.valid', '=', DB::raw(1));
                })
                    ->leftJoin('software_internal_link_access', function($join) use ($user_id) {
                        $join->on('software_internal_link.id', '=', 'software_internal_link_access.link_id');
                        $join->on('software_internal_link_access.user_id', '=', DB::raw($user_id));
                        $join->on('software_internal_link_access.valid', '=', DB::raw(1));
                    })
                    ->select('software_internal_link.*', 'software_internal_link_access.id as link_access')
                    ->where('software_internal_link.menu_id', $software_menu->id)
                    ->where('software_internal_link.status', 1)
                    ->where('software_internal_link.valid', 1)
                    ->orderBy('id', 'asc')
                    ->get();

                if($checkAll) { if(empty($software_menu->menu_access)) { $checkAll = false; } }
                    foreach($software_menu->internal_links as $internal_links) {
                        if($checkAll) { if(empty($internal_links->link_access)) { $checkAll = false; } }
                    }
            }

            $data['software_menus'] = $software_menus;
            $data['checkAll'] = $checkAll;

            return view('provider.admin.providerUsers.employeeAccessMenuView', $data);
        }
    }


    public function employeeAccessAction(Request $request)
    {
        DB::beginTransaction();
        $user_id = $request->input('user');
        $module_id = $request->input('module');
        $menu = $request->input('menu');
        $link = $request->input('internal_link');
        $project_id = Auth::guard('provider')->user()->project_id;

        $menu_db = collect(SoftwareMenu_provider::providerUserAccessMenus($user_id, $module_id)->pluck('id'));
        $link_db = collect(SoftwareInternalLinkAccess_provider::select('software_internal_link_access.*')
            ->join('software_internal_link', 'software_internal_link_access.link_id', '=', 'software_internal_link.id')
            ->join('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
            ->where('software_menu.module_id', $module_id)
            ->where('software_internal_link_access.user_id', $user_id)
            ->where('software_menu.valid', 1)
            ->where('software_internal_link.valid', 1)
            ->where('software_internal_link_access.valid', 1)
            ->get()->pluck('link_id'));

        $menu_diff = $menu_db->diff($menu);
        $link_diff = $link_db->diff($link);

        if(!empty($menu_diff)) {
            $cur_menu_ac = SoftwareMenuAccess_provider::whereIn('menu_id', $menu_diff)->where('user_id', $user_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                SoftwareMenuAccess_provider::find($cur_menu->id)->delete();
            }
        }
        if(!empty($link_diff)) {
            $cur_link_ac = SoftwareInternalLinkAccess_provider::whereIn('link_id', $link_diff)->where('user_id', $user_id)->get();
            foreach($cur_link_ac as $cur_link) {
                SoftwareInternalLinkAccess_provider::find($cur_link->id)->delete();
            }
        }

        if(!empty($menu)) {
            $projectAccess_menu = collect(ProjectAccess_provider::valid()->where('type', 1)->where('project_id', $project_id)->get()->pluck('menu_id'));
            foreach($menu as $menu_id) {
                if($projectAccess_menu->contains($menu_id) && !$menu_db->contains($menu_id)) {
                    $menu_id = (int)$menu_id;  //Here is problem in online server (for int)
                    SoftwareMenuAccess_provider::create(array(
                        'menu_id' => $menu_id,
                        'user_id' => $user_id
                    ));
                }
            }
        }

        if(!empty($link)) {
            $projectAccess_link = collect(ProjectAccess_provider::valid()->where('type', 2)->where('project_id', $project_id)->get()->pluck('link_id'));
            foreach($link as $link_id) {
                if($projectAccess_link->contains($link_id) && !$link_db->contains($link_id)) {
                    SoftwareInternalLinkAccess_provider::create(array(
                        'link_id' => $link_id,
                        'user_id' => $user_id
                    ));
                }
            }
        }
        DB::commit();
    }


    public function employeeAccessView(Request $request)
    {
        $user_id = $request->user_id;
        $module_id = $request->module_id;
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'link_name', 'module_name', 'updated_at'], ['updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $userAccess = DB::table("software_access")
            ->where('user_id', $user_id)
            ->where(function($query) use ($search)
            {
                $query->where('menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            });
        if(!empty($module_id)) {
            $userAccess = $userAccess->where('module_id', $module_id);
        }
        $data['userAccess'] = $userAccess->orderBy($ascDesc[0], $ascDesc[1])->paginate($paginate->perPage);
        
        return view('provider.admin.providerUsers.employeeAccessView', $data);
    }


}
