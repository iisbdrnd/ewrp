<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CompanyHistory_provider;

class CompanyHistoryController extends Controller
{
    public function index(Request $request)
    {
        $companyHistory = CompanyHistory_provider::valid()->first();
        if(!empty($companyHistory)){
            return self::edit($companyHistory->id);
        }else{
            return self::create();
        }
    }

    public function create()
    {
        return view('provider.eastWest.companyHistory.create');
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'description'     => 'required'
        ]);

        if ($validator->passes()) {
            CompanyHistory_provider::create($input);
            $output['messege'] = 'Company History has been created';
            $output['msgType'] = 'success';

            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['companyHistory'] = CompanyHistory_provider::valid()->find($id);
        return view('provider.eastWest.companyHistory.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::guard('provider')->user()->project_id;

        $validator = Validator::make($input, [
            'description'     => 'required'
        ]);

        if ($validator->passes()) {
        CompanyHistory_provider::valid()->find($id)->update($input);
        $output['messege'] = 'Company History has been updated';
        $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }
}
