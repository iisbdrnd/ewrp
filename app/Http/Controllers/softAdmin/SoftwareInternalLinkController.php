<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Helper;

//Model
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\SoftwareModules;
use App\Model\Icon;

class SoftwareInternalLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Internal Link';

        return view('softAdmin.softwareInternalLink.index', $data);
    }

    public function softwareInternalLinkList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['folder_name', 'link_name', 'route', 'menu_id', 'status']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['softwareInternalLinkLists'] = SoftwareInternalLink::leftJoin('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->join('software_folder', 'software_modules.folder_id', '=', 'software_folder.id')
            ->select('software_internal_link.*', 'software_folder.folder_name', DB::raw("if(software_internal_link.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_internal_link.route), concat(software_modules.route_prefix, software_internal_link.route)) as route"), 'software_menu.menu_name', 'software_modules.module_name')
            ->where(function($query) use ($search)
            {
                $query->where('software_internal_link.link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_internal_link.route', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_menu.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_folder.folder_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_modules.module_name', 'LIKE', '%'.$search.'%');
                
            })
            ->where('software_internal_link.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.softwareInternalLink.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {   
        $data['modules'] = SoftwareModules::orderBy('module_name', 'asc')->get();
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();
        
        return view('softAdmin.softwareInternalLink.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        if(empty($input['status'])) {$input['status'] = 0;}

        if(empty($input['resource'])) {
            $input['resource'] = 0;
            SoftwareInternalLink::create($input);
        } else {
            $link_name_add = $input['link_name_add']; unset($input['link_name_add']);
            $link_name_edit = $input['link_name_edit']; unset($input['link_name_edit']);
            $link_name_delete = $input['link_name_delete']; unset($input['link_name_delete']);
            $link_name_show = $input['link_name_show']; unset($input['link_name_show']);
            $route = $input['route'];

            if(!empty($link_name_add)) {
                $input['link_name'] = $link_name_add;
                $input['route'] = $route.'.create';
                SoftwareInternalLink::create($input);
            }
            if(!empty($link_name_edit)) {
                $input['link_name'] = $link_name_edit;
                $input['route'] = $route.'.edit';
                SoftwareInternalLink::create($input);
            }
            if(!empty($link_name_delete)) {
                $input['link_name'] = $link_name_delete;
                $input['route'] = $route.'.destroy';
                SoftwareInternalLink::create($input);
            }
            if(!empty($link_name_show)) {
                $input['link_name'] = $link_name_show;
                $input['route'] = $route.'.show';
                SoftwareInternalLink::create($input);
            }
        }

        if(!empty($input['resource']) && (empty($link_name_add) && empty($link_name_edit) && empty($link_name_delete) && empty($link_name_show))) {
            $output['messege'] = 'Please give at least one link.';
            $output['msgType'] = 'danger';
        } else {
            $output['messege'] = 'Link has been created';
            $output['msgType'] = 'success';
        }
        echo json_encode($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['modules'] = SoftwareModules::orderBy('module_name', 'asc')->get();
        $data['softwareInternalLinks'] = $softwareInternalLink = SoftwareInternalLink::find($id);
        $data['softwareMenu'] = $softwareMenu = SoftwareMenu::find($softwareInternalLink->menu_id);
        $data['internalLinkmodule'] = SoftwareModules::find($softwareMenu->module_id);

        $data['softwareLinkMenus'] =SoftwareMenu::where('module_id', $softwareMenu->module_id)->active()->get();
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();

        return view('softAdmin.softwareInternalLink.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        if(empty($input['status'])) {$input['status'] = 0;}

        if(empty($input['resource'])) {
            $input['resource'] = 0;
        }

        $output['messege'] = 'Link has been updated';
        $output['msgType'] = 'success';
        SoftwareInternalLink::find($id)->update($input);
            
        echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        SoftwareInternalLink::find($id)->delete();
    }

    public function softwareInternalLinkModule(Request $request)
    {
        $folder_id=$request->folder_id;
        $data['softwareLinkModules'] =SoftwareModules::where('folder_id', $folder_id)->active()->get();
        return view('softAdmin.softwareInternalLink.softwareInternalLinkModule', $data);
    }
    
    
    public function softwareLinkMenu(Request $request)
    {
        $module_id=$request->module_name;
        $data['softwareLinkMenus'] =SoftwareMenu::where('module_id', $module_id)->active()->get();
        return view('softAdmin.softwareInternalLink.softwareLinkMenu', $data);
    }

}
