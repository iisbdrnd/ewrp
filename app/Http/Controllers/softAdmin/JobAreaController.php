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
use App\Model\CrmJobArea_admin;

class JobAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('softAdmin.jobArea.list', $data);
    }

    public function jobAreaListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['area_name', 'area_details', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['jobArea'] = CrmJobArea_admin::leftJoin('project', function($join)
            {
                $join->on('crm_job_area.project_id', '=', 'project.id')
                     ->on('project.valid', '=', DB::raw(1));
            })
            ->select('crm_job_area.*', 'project.project_id as project_id_view', 'project.name as project_name')
            ->where('crm_job_area.valid', 1)
            ->where(function($query) use ($search)
            {
                $query->where('crm_job_area.area_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('crm_job_area.updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.jobArea.listData', $data);
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
        
        return view('softAdmin.jobArea.create', $data);
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
            'project_id'        => 'required',
            'area_name'         => 'required',
            'area_details'      => 'required'
        ]);

        if ($validator->passes()) {
            CrmJobArea_admin::create($input);
            $output['messege'] = 'Job Area has been created';
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
        $data['adminJobArea'] = CrmJobArea_admin::valid()->find($id);
        $data['projects'] = Project::where('valid', 1)->orderBy('project_id', 'asc')->get();

        return view('softAdmin.jobArea.update', $data);
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
            'project_id'        => 'required',
            'area_name'         => 'required',
            'area_details'      => 'required'
        ]);

        if ($validator->passes()) {
        CrmJobArea_admin::valid()->find($id)->update($input);
        $output['messege'] = 'Area has been updated';
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
        CrmJobArea_admin::valid()->find($id)->delete();
    }
}
