<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Helper;

//Model
use App\Model\SoftwareMenu;
use App\Model\AdminInternalLink;
use App\Model\AdminMenu;
use App\Model\SoftwareModules;
use App\Model\Icon;

class AdminInternalLinkController extends Controller
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

        return view('softAdmin.adminInternalLink.index', $data);
    }

    public function adminInternalLinkList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['link_name', 'route', 'menu_id', 'status']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['adminInternalLink'] = AdminInternalLink::leftJoin('admin_menu', 'admin_internal_link.menu_id', '=', 'admin_menu.id')
            ->select('admin_internal_link.*', 'admin_menu.menu_name')
            ->where(function($query) use ($search)
            {
                $query->where('admin_internal_link.link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('admin_internal_link.route', 'LIKE', '%'.$search.'%')
                    ->orWhere('admin_menu.menu_name', 'LIKE', '%'.$search.'%');
                
            })
            ->where('admin_internal_link.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.adminInternalLink.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {   
        $data['adminMenus'] = AdminMenu::valid()->active()->orderBy('sl_no', 'asc')->get();
        return view('softAdmin.adminInternalLink.create', $data);
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
            AdminInternalLink::create($input);
        } else {
            $link_name_add = $input['link_name_add']; unset($input['link_name_add']);
            $link_name_edit = $input['link_name_edit']; unset($input['link_name_edit']);
            $link_name_delete = $input['link_name_delete']; unset($input['link_name_delete']);
            $link_name_show = $input['link_name_show']; unset($input['link_name_show']);
            $route = $input['route'];

            if(!empty($link_name_add)) {
                $input['link_name'] = $link_name_add;
                $input['route'] = $route.'.create';
                AdminInternalLink::create($input);
            }
            if(!empty($link_name_edit)) {
                $input['link_name'] = $link_name_edit;
                $input['route'] = $route.'.edit';
                AdminInternalLink::create($input);
            }
            if(!empty($link_name_delete)) {
                $input['link_name'] = $link_name_delete;
                $input['route'] = $route.'.destroy';
                AdminInternalLink::create($input);
            }
            if(!empty($link_name_show)) {
                $input['link_name'] = $link_name_show;
                $input['route'] = $route.'.show';
                AdminInternalLink::create($input);
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
        $data['adminInternalLink'] = $adminInternalLink = AdminInternalLink::find($id);
        $data['adminMenus'] = AdminMenu::valid()->active()->orderBy('sl_no', 'asc')->get();
        return view('softAdmin.adminInternalLink.update', $data);
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
        AdminInternalLink::find($id)->update($input);
            
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
        AdminInternalLink::valid()->find($id)->delete();
    }

}
