<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Helper;

//Model
use App\Model\SoftwareModules;
use App\Model\Icon;

class SoftwareModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Module';

        return view('softAdmin.softwareModule.index', $data);
    }

    public function softwareModuleList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['folder_name', 'module_name', 'module_icon', 'url_prefix', 'route_prefix', 'status']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['softwareModules'] = SoftwareModules::join('software_folder', 'software_modules.folder_id', '=', 'software_folder.id')
                ->select('software_modules.*', 'software_folder.folder_name')
                ->where(function($query) use ($search)
                {
                    $query->where('software_modules.module_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('software_modules.status', 'LIKE', '%'.$search.'%')
                        ->orWhere('software_modules.url_prefix', 'LIKE', '%'.$search.'%')
                        ->orWhere('software_modules.route_prefix', 'LIKE', '%'.$search.'%')
                        ->orWhere('software_folder.folder_name', 'LIKE', '%'.$search.'%');
                })
            ->where('software_modules.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.softwareModule.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['icons'] = Icon::orderBy('class_name', 'asc')->get();
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();
        
        return view('softAdmin.softwareModule.create', $data);
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
            $output['messege'] = 'Module has been created';
            $output['msgType'] = 'success';
            SoftwareModules::create($input);
            
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
        $data['icons'] = Icon::orderBy('class_name', 'asc')->get();
        $data['software_modules'] = SoftwareModules::find($id);
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();

        return view('softAdmin.softwareModule.update', $data);
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

            $output['messege'] = 'Module has been updated';
            $output['msgType'] = 'success';
            SoftwareModules::find($id)->update($input);
            
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
        SoftwareModules::find($id)->delete();
    }

   
}
