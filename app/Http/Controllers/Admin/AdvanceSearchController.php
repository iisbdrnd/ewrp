<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;

use Validator;
use DateTime;

use App\Model\EmployeeBasicInfo_user;
use App\Model\CrmSalesTarget;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdvanceSearchController extends Controller
{
    public function employeeSearch(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['name', 'designation_name', 'area_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $project_id = Auth::user()->get()->project_id;

        $employeeId = CrmSalesTarget::valid()->get()->pluck('user_id')->all();

        $data['employeeBasicInfo'] = EmployeeBasicInfo_user::leftJoin('employee_designation', function($join) use ($project_id) {
                $join->on('employee_basic_info.designation', '=', 'employee_designation.id')
                    ->on('employee_basic_info.project_id','=', DB::raw($project_id))
                    ->on('employee_basic_info.valid','=', DB::raw(1));
                })
            ->leftJoin('crm_job_area', function($join) use ($project_id) {
                $join->on('employee_basic_info.job_area', '=', 'crm_job_area.id')
                    ->on('crm_job_area.project_id','=', DB::raw($project_id))
                    ->on('crm_job_area.valid','=', DB::raw(1));
                })
            ->select('employee_basic_info.*', 'employee_designation.name as designation_name', 'crm_job_area.area_name')
            ->where(function($query) use ($search)
            {
                $query->where('employee_basic_info.name', 'LIKE', '%'.$search.'%')
                        ->orWhere('crm_job_area.area_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('employee_designation.name', 'LIKE', '%'.$search.'%');
            })
            ->whereNotIn('employee_basic_info.user_id', $employeeId)
            ->where('employee_basic_info.project_id', $project_id)
            ->where('employee_basic_info.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);


        return view('admin.advanceSearch.employeeSearch', $data);
    }

}

