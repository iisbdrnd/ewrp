<?php

namespace App\Http\Controllers\Provider\Tubingen;

use DB;
use Auth;
use Helper;
use Validator;
use App\Http\Requests;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TermsAndCondition_provider;

class TermsAndConditionController extends Controller
{
    public function index(Request $request)
    {
        $aboutUs = TermsAndCondition_provider::valid()->first();
        if(!empty($aboutUs)){
            return self::edit($aboutUs->id);
        }else{
            return self::create();
        }
    }

    public function create()
    {
        return view('provider.eastWest.termsAndCondition.create');
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'terms_and_condition' => 'required'
        ]);

        if ($validator->passes()) {
            TermsAndCondition_provider::create($input);
            $output['messege'] = 'Terms and condition has been created';
            $output['msgType'] = 'success';

        } 
        else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function show($id){

    }

    public function edit($id)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['conditions'] = TermsAndCondition_provider::valid()->find($id);
        return view('provider.eastWest.termsAndCondition.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::guard('provider')->user()->project_id;

        $validator = Validator::make($input, [
            'terms_and_condition' => 'required'
        ]);

        if ($validator->passes()) {
            TermsAndCondition_provider::valid()->find($id)->update($input);
            $output['messege'] = 'Terms and condition has been updated';
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
        TermsAndCondition_provider::valid()->find($id)->delete();
    }
}
