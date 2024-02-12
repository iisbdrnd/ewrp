<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use Helper;
use App\Model\Admin;
use App\Model\AdminMenu;
use App\Model\AdminInternalLink;
use App\Model\AdminMenuAccess;
use App\Model\AdminInternalLinkAccess;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AdminAccessController extends Controller
{
    public function adminAccess(Request $request)
    {
        $admin_id = $request->input('data');
        $data['admin_info'] = admin::active()->find($admin_id);
        $checkAll = true;

        $admin_menus = AdminMenu::select('admin_menu.*', 'admin_menu_access.id as menu_access')
            ->leftJoin('admin_menu_access', function($join) use ($admin_id) {
                $join->on('admin_menu.id', '=', 'admin_menu_access.menu_id');
                $join->on('admin_menu_access.admin_id', '=', DB::raw($admin_id));
                $join->on('admin_menu_access.valid', '=', DB::raw(1));
            })
            ->where('admin_menu.status', 1)
            ->where('admin_menu.valid', 1)
            ->orderBy('admin_menu.sl_no', 'asc')
            ->get();

        foreach($admin_menus as $admin_menu) {
            $admin_menu->internal_links = AdminInternalLink::select('admin_internal_link.*', 'admin_internal_link_access.id as link_access')
                ->leftJoin('admin_internal_link_access', function($join) use ($admin_id) {
                    $join->on('admin_internal_link.id', '=', 'admin_internal_link_access.link_id');
                    $join->on('admin_internal_link_access.admin_id', '=', DB::raw($admin_id));
                    $join->on('admin_internal_link_access.valid', '=', DB::raw(1));
                })
                ->where('admin_internal_link.menu_id', $admin_menu->id)
                ->where('admin_internal_link.status', 1)
                ->where('admin_internal_link.valid', 1)
                ->orderBy('admin_internal_link.id', 'asc')
                ->get();

            if($checkAll) { if(empty($admin_menu->menu_access)) { $checkAll = false; } }
            foreach($admin_menu->internal_links as $internal_links) {
                if($checkAll) { if(empty($internal_links->link_access)) { $checkAll = false; } }
            }
        }

        $data['admin_menus'] = $admin_menus;
        $data['checkAll'] = $checkAll;

        return view('softAdmin.admin.adminAccess', $data);
    }

    public function adminAccessAction(Request $request)
    {
        DB::beginTransaction();
        $admin_id = $request->input('admin');
        $menu = $request->input('menu');
        $link = $request->input('internal_link');

        $menu_data = AdminMenu::active()->get();
        $menu_db = collect(AdminMenu::adminAccessMenus($admin_id)->pluck('id'));
        $link_db = collect(AdminInternalLinkAccess::select('admin_internal_link_access.*')
            ->join('admin_internal_link', 'admin_internal_link_access.link_id', '=', 'admin_internal_link.id')
            ->join('admin_menu', 'admin_internal_link.menu_id', '=', 'admin_menu.id')
            ->where('admin_internal_link_access.admin_id', $admin_id)
            ->where('admin_menu.valid', 1)
            ->where('admin_internal_link.valid', 1)
            ->where('admin_internal_link_access.valid', 1)
            ->get()->pluck('link_id'));

        $menu_diff = $menu_db->diff($menu);
        $link_diff = $link_db->diff($link);

        if(!empty($menu_diff)) {
            $cur_menu_ac = AdminMenuAccess::whereIn('menu_id', $menu_diff)->where('admin_id', $admin_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                AdminMenuAccess::find($cur_menu->id)->delete();
            }
        }
        if(!empty($link_diff)) {
            $cur_link_ac = AdminInternalLinkAccess::whereIn('link_id', $link_diff)->where('admin_id', $admin_id)->get();
            foreach($cur_link_ac as $cur_link) {
                AdminInternalLinkAccess::find($cur_link->id)->delete();
            }
        }

        if(!empty($menu)) {
            foreach($menu as $menu_id) {
                if(!$menu_db->contains($menu_id)) {
                    AdminMenuAccess::create(array(
                        'menu_id' => $menu_id,
                        'admin_id' => $admin_id
                    ));
                }
            }
        }

        if(!empty($link)) {
            foreach($link as $link_id) {
                if(!$link_db->contains($link_id)) {
                    AdminInternalLinkAccess::create(array(
                        'link_id' => $link_id,
                        'admin_id' => $admin_id
                    ));
                }
            }
        }
        DB::commit();
    }
    
    public function adminAccessView(Request $request)
    {
        $admin_id = $request->admin_id;
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'link_name', 'updated_at'], ['updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['adminAccess'] = DB::table("admin_access")
            ->where('admin_id', $admin_id)
            ->where(function($query) use ($search)
            {
                $query->where('menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
        
        return view('softAdmin.admin.adminAccessView', $data);
    }


}
