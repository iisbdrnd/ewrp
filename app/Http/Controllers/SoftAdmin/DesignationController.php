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
use App\Model\EmployeeDesignation_admin;
use App\Model\Project;

class DesignationController extends Controller
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

    public function designationListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['name', 'grade', 'project_id_view', 'project_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        
        $data['adminDesignation'] = EmployeeDesignation_admin::leftJoin('project', function($join)
            {
                $join->on('employee_designation.project_id', '=', 'project.id')
                     ->on('project.valid', '=', DB::raw(1));
            })
            ->select('employee_designation.*', 'project.project_id as project_id_view', 'project.name as project_name')
            ->where('employee_designation.valid', 1)
            ->where(function($query) use ($search)
            {
                $query->where('employee_designation.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('employee_designation.grade', 'LIKE', '%'.$search.'%')
                    ->orWhere('project.project_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('project.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('employee_designation.updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.designation.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['projects'] = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();
        
        return view('softAdmin.designation.create', $data);
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
            EmployeeDesignation_admin::create($input);
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
        $data['adminDesignation'] = EmployeeDesignation_admin::valid()->find($id);
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

        $validator = Validator::make($input, [
            "project_id" => "required",
            "name"       => "required",
            "grade"      => "required"
        ]);

        if ($validator->passes()) {
        EmployeeDesignation_admin::valid()->find($id)->update($input);
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
        EmployeeDesignation_admin::valid()->find($id)->delete();
    }
}
