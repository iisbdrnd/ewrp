<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Department_provider;

class departmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.admin.department.list', $data);
    }

    public function departmentListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['departments'] = Department_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.admin.department.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.admin.department.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->passes()) {
            Department_provider::create($input);
            $output['messege'] = 'Department has been created';
            $output['msgType'] = 'success';

            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }


    public function edit($id)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['department'] = Department_provider::valid()->find($id);
        return view('provider.admin.department.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::guard('provider')->user()->project_id;

        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
        Department_provider::valid()->find($id)->update($input);
        $output['messege'] = 'Department has been updated';
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
        Department_provider::valid()->find($id)->delete();
    }
}
