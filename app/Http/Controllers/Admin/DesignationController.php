<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminDesignation;
use App\Model\EmployeeDesignation_user;

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
        
        return view('admin.designation.list', $data);
    }

    public function designationListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['name', 'grade', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['adminDesignation'] = EmployeeDesignation_user::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('grade', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('admin.designation.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('admin.designation.create', $data);
    }

    public function add()
    {
        return view('admin.designation.add');
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
            'name' => 'required',
            'grade' => 'required'
        ]);

        if ($validator->passes()) {
            EmployeeDesignation_user::create($input);
            $output['messege'] = 'Designation has been created';
            $output['msgType'] = 'success';

            //For In Page Add
            $crmDesignationInfo = EmployeeDesignation_user::valid()->orderBy('id', 'desc')->first();
            $output['value'] = $crmDesignationInfo->id;
            $output['text'] = $crmDesignationInfo->name;
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
        $project_id = Auth::user()->get()->project_id;
        
        $data['adminDesignation'] = EmployeeDesignation_user::valid()->find($id);
        return view('admin.designation.update', $data);
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
        $project_id = Auth::user()->get()->project_id;

        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
        EmployeeDesignation_user::valid()->find($id)->update($input);
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
        EmployeeDesignation_user::valid()->find($id)->delete();
    }
}
