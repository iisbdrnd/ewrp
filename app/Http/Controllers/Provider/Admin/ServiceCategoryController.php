<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\AdminDesignation;
use App\Model\ServiceCategory_provider;
use App\Model\EmployeeUser_provider;
use App\Model\ActionPlanners_provider;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.admin.serviceCategory.list', $data);
    }

    public function serviceCategoryListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['serviceCategories'] = ServiceCategory_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('description', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.admin.serviceCategory.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['employees'] = EmployeeUser_provider::valid()->get();
        return view('provider.admin.serviceCategory.create', $data);
    }

    public function add()
    {
        return view('provider.admin.serviceCategory.add');
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $employee_ids = $request->employee_id;
        $validator = Validator::make($input, [
            'name'          => 'required',
        ]);

        if ($validator->passes()) {
           $serviceCategory =ServiceCategory_provider::create([
                'name'              => $request->name,
                'description'       => $request->description,
                'approval_status'   => $request->approval_status,
            ]);
            foreach ($employee_ids as $employee_id) {
                ActionPlanners_provider::create([
                    'service_category_id'    => $serviceCategory->id,
                    'employee_id'            => $employee_id,
                    'contact_person_status'  => 0,
                ]);
            }
            $output['messege'] = 'Service Category has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['employees'] = EmployeeUser_provider::valid()->get();
        $data['serviceCategory'] = ServiceCategory_provider::valid()->find($id);

        $data['serviceCategoryEmployees']= ServiceCategory_provider::join('action_planners','action_planners.service_category_id','=','service_category.id')
            ->join('en_provider_user','en_provider_user.id','=','action_planners.employee_id')
            ->select('en_provider_user.id as employees_id')
            ->where('service_category.valid',1)
            ->where('action_planners.valid',1)
            ->where('service_category.id',$id)
            ->get()
            ->pluck('employees_id')
            ->toArray();
        return view('provider.admin.serviceCategory.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $New_employee_arr = $request->employee_id;
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            $serviceCategory = ServiceCategory_provider::valid()->find($id)->update([
                'name'              => $request->name,
                'description'       => $request->description,
                'approval_status'   => $request->approval_status,
            ]);
            $old_employee = ActionPlanners_provider::valid()->where('service_category_id', $id)->get()->keyBy('employee_id')->all();
            $new_employee_ids = array_filter($New_employee_arr);
            if (!empty($old_employee)) {
                foreach($old_employee as $key => $oldValue) {
                    if(!in_array($key, $new_employee_ids)) {
                        ActionPlanners_provider::find($oldValue->id)->delete();
                    }
                }
            }

            foreach($new_employee_ids as $key => $employee_id)
            {
                if (!empty($old_employee)) {
                    if(!array_key_exists($employee_id, $old_employee)) {
                        ActionPlanners_provider::create([
                            'service_category_id'    => $id,
                            'employee_id'            => $employee_id,
                            'contact_person_status'  => 0,
                        ]);
                    }
                } else {
                    ActionPlanners_provider::create([
                        'service_category_id'    => $id,
                        'employee_id'            => $employee_id,
                        'contact_person_status'  => 0,
                    ]);
                }
            }

            $output['messege'] = 'Service Category has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        ServiceCategory_provider::valid()->find($id)->delete();

    }

    public function actionplanner(Request $request)
    {
        $data['planners'] = ActionPlanners_provider::join('en_provider_user','en_provider_user.id','=','action_planners.employee_id')
            ->select('action_planners.id','action_planners.service_category_id','action_planners.contact_person_status','en_provider_user.name')
            ->where('action_planners.service_category_id',$request->s_cat)
            ->where('action_planners.valid', 1)
            ->get();
        return view('provider.admin.serviceCategory.actionPlanner', $data);
    }
    public function plannerAction(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'planner_id' => 'required'
        ]);
        if ($validator->passes()) {
            $service_cat_id = ActionPlanners_provider::valid()->where('id',$request->planner_id)->first();
            $data['planners']= $planners = ActionPlanners_provider::valid()->where('service_category_id',$service_cat_id->service_category_id)->get()->pluck('contact_person_status')->toArray();
            if (!empty($planners)) {
                if(in_array(1, $planners)) {
                    ActionPlanners_provider::where('service_category_id',$service_cat_id->service_category_id)
                    ->where('contact_person_status',1)->update([
                        'contact_person_status' => 0,
                    ]);
                }
            }
            $serviceCategory = ActionPlanners_provider::valid()->find($request->planner_id)->update([
                'contact_person_status' => 1,
            ]);

            $output['messege'] = 'Service Category has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }
}
