<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminDesignation;
use App\Model\Project;
use App\Model\EnProviderDesignation_admin;
use App\Model\EmployeeDesignation_user;

class ProviderDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('softAdmin.designation.list', $data);
    }

    public function proDesignationListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['name', 'grade', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['enProviderDesignations'] = EnProviderDesignation_admin::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('grade', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);


        return view('softAdmin.designation.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['projects'] = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();
        
        return view('softAdmin.designation.create', $data);
    }

    public function add()
    {
        return view('softAdmin.designation.add');
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
        
        $validator = Validator::make($input, [
            "project_id" => "required",
            "name"       => "required",
            "grade"      => "required"
        ]);

        if ($validator->passes()) {
            EnProviderDesignation_admin::create($input);
            $output['messege'] = 'Designation has been created';
            $output['msgType'] = 'success';
            } else {
            $output = Helper::vError($validator);
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
        $data['projects'] = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();
        $data['adminDesignation'] = EnProviderDesignation_admin::valid()->find($id);
        return view('softAdmin.designation.update', $data);
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
        // $data['projects'] = $projects = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();

        $validator = Validator::make($input, [
            "project_id" => "required",
            "name"       => "required",
            "grade"      => "required"
        ]);

        if ($validator->passes()) {
        EnProviderDesignation_admin::valid()->find($id)->update($input);
        $output['messege'] = 'Designation has been updated';
        $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
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
        EnProviderDesignation_admin::valid()->find($id)->delete();
    }
}
