<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use Helper;
use App\Model\Project;
use App\Model\ProjectAccess;
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\SoftwareMenuAccess_admin;
use App\Model\SoftwareInternalLinkAccess_admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ProjectAccessController extends Controller
{
    public function projectAccess(Request $request)
    {
        $project_id = $request->input('data');
        $data['project_info'] = Project::valid()->find($project_id);
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();

        return view('softAdmin.project.projectAccess', $data);
    }

    public function projectAccessModuleViewByFolder(Request $request)
    {
        $folder_id = $request->folder_id;
        $data['software_modules'] = SoftwareModules::active()->where('folder_id', $folder_id)->orderBy('sl_no', 'asc')->get()->chunk(4);

        return view('softAdmin.project.projectAccessModuleViewByFolder', $data);
    }

    public function projectAccessMenuView(Request $request)
    {
        $module_id = $request->module_id;
        $project_id = $request->project_id;
        $data['software_module'] = SoftwareModules::active()->find($module_id);
        $checkAll = true;

        if(!empty($data['software_module'])) {
            $software_menus = SoftwareMenu::active()
                ->select('software_menu.*', 'project_access.id as menu_access')
                ->leftJoin('project_access', function($join) use ($project_id) {
                    $join->on('software_menu.id', '=', 'project_access.menu_id');
                    $join->on('project_access.project_id', '=', DB::raw($project_id));
                    $join->on('project_access.type', '=', DB::raw(1));
                    $join->on('project_access.valid', '=', DB::raw(1));
                })
                ->where('software_menu.module_id', $module_id)
                ->where('software_menu.valid', 1)
                ->orderBy('software_menu.sl_no', 'asc')
                ->get();

            foreach($software_menus as $software_menu) {
                $software_menu->internal_links = SoftwareInternalLink::active()
                    ->select('software_internal_link.*', 'project_access.id as link_access')
                    ->leftJoin('project_access', function($join) use ($project_id) {
                        $join->on('software_internal_link.id', '=', 'project_access.link_id');
                        $join->on('project_access.project_id', '=', DB::raw($project_id));
                        $join->on('project_access.type', '=', DB::raw(2));
                        $join->on('project_access.valid', '=', DB::raw(1));
                    })
                    ->where('software_internal_link.menu_id', $software_menu->id)
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

            return view('softAdmin.project.projectAccessMenuView', $data);
        }
    }

    public function projectAccessAction(Request $request)
    {
        DB::beginTransaction();
        $project_id = $request->input('project');
        $module_id = $request->input('module');
        $menu = $request->input('menu');
        $link = $request->input('internal_link');

        $menu_db = collect(SoftwareMenu::projectAccessMenus($project_id, $module_id)->pluck('id'));
        $link_db = collect(SoftwareInternalLink::projectAccessLinks($project_id, $module_id)->pluck('link_id'));

        $menu_diff = $menu_db->diff($menu);
        $link_diff = $link_db->diff($link);

        if(!empty($menu_diff)) {
            //Remove Project Access
            $cur_menu_ac = ProjectAccess::valid()->whereIn('menu_id', $menu_diff)->where('type', 1)->where('project_id', $project_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                ProjectAccess::valid()->find($cur_menu->id)->delete();
            }
            //Remove User Access
            $cur_menu_ac = SoftwareMenuAccess_admin::valid()->whereIn('menu_id', $menu_diff)->where('project_id', $project_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                SoftwareMenuAccess_admin::valid()->find($cur_menu->id)->delete();
            }
        }
        if(!empty($link_diff)) {
            //Remove Project Access
            $cur_link_ac = ProjectAccess::valid()->whereIn('link_id', $link_diff)->where('type', 2)->where('project_id', $project_id)->get();
            foreach($cur_link_ac as $cur_link) {
                ProjectAccess::valid()->find($cur_link->id)->delete();
            }
            //Remove User Access
            $cur_link_ac = SoftwareInternalLinkAccess_admin::valid()->whereIn('link_id', $link_diff)->where('project_id', $project_id)->get();
            foreach($cur_link_ac as $cur_link) {
                SoftwareInternalLinkAccess_admin::valid()->find($cur_link->id)->delete();
            }
        }

        if(!empty($menu)) {
            foreach($menu as $menu_id) {
                if(!$menu_db->contains($menu_id)) {
                    ProjectAccess::create(array(
                        'menu_id' => $menu_id,
                        'type' => 1,
                        'project_id' => $project_id
                    ));
                }
            }
        }

        if(!empty($link)) {
            foreach($link as $link_id) {
                if(!$link_db->contains($link_id)) {
                    ProjectAccess::create(array(
                        'link_id' => $link_id,
                        'type' => 2,
                        'project_id' => $project_id
                    ));
                }
            }
        }
        DB::commit();
    }
    
    public function projectAccessView(Request $request)
    {
        $project_id = $request->project_id;
        $module_id = $request->module_id;
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'link_name', 'module_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $projectAccess = ProjectAccess::leftJoin('software_menu as a', 'project_access.menu_id', '=', 'a.id')
            ->leftJoin('software_internal_link', 'project_access.link_id', '=', 'software_internal_link.id')
            ->leftJoin('software_menu as b', 'software_internal_link.menu_id', '=', 'b.id')
            ->leftJoin('software_modules as c', 'a.module_id', '=', 'c.id')
            ->leftJoin('software_modules as d', 'b.module_id', '=', 'd.id')
            ->select('project_access.*', DB::raw("if(project_access.link_id=0, a.menu_name, b.menu_name) as menu_name"), 'software_internal_link.link_name', DB::raw("if(project_access.link_id=0, c.module_name, d.module_name) as module_name"))
            ->where('project_access.project_id', $project_id)
            ->where(function($query) use ($search)
            {
                $query->where('a.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('b.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_internal_link.link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('c.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('d.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('project_access.updated_at', 'LIKE', '%'.$search.'%');
            });
        if(!empty($module_id)) {
            $projectAccess = $projectAccess->where(function($query) use ($module_id)
            {
                $query->where('a.module_id', $module_id)
                    ->orWhere('b.module_id', $module_id);
            });
        }
        $data['projectAccess'] = $projectAccess->orderBy($ascDesc[0], $ascDesc[1])->paginate($paginate->perPage);
        return view('softAdmin.project.projectAccessView', $data);
    }


}
