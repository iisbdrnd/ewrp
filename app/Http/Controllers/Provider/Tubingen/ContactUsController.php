<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\ContactUs_provider;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        $contactUs = ContactUs_provider::valid()->first();
        if(!empty($contactUs)){
            return self::edit($contactUs->id);
        }else{
            return self::create();
        }
    }

    public function create()
    {
        return view('provider.eastWest.contactUs.create');
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'phone'                   => 'required',
            'email'                   => 'required',
            'head_office_address'     => 'required',
            'training_center_address' => 'required'
        ]);

        if ($validator->passes()) {
            ContactUs_provider::create($input);
            $output['messege'] = 'Contact Us has been created';
            $output['msgType'] = 'success';

            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['contactUs'] = ContactUs_provider::valid()->find($id);
        return view('provider.eastWest.contactUs.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::guard('provider')->user()->project_id;

        $validator = Validator::make($input, [
            'head_office_phone'       => 'required',
            'head_office_email'       => 'required',
            'head_office_address'     => 'required',
            'training_center_phone'   => 'required',
            'training_center_email'   => 'required',
            'training_center_address' => 'required'
        ]);

        if ($validator->passes()) {
        ContactUs_provider::valid()->find($id)->update($input);
        $output['messege'] = 'Contact Us has been updated';
        $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        ContactUs_provider::valid()->find($id)->delete();
    }
}
