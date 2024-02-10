<?php

namespace App\Http\Controllers\Provider\ApprovalSystem;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function socialMediaConfiguration(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['sclConfig'] = DB::table('en_social_media_configuration')->first();

        return view('provider.ApprovalSystem.configuration.socialMediaConfiguration', $data);
    }

    //Action
    public function socialMediaConfigurationUpdate(Request $request)
    {
        DB::table('en_social_media_configuration')->update([
            "facebook" => $request->facebook,
            "twitter" => $request->twitter,
            "google_plus" => $request->google_plus,
            "linkedin" => $request->linkedin,
            "pinterest" => $request->pinterest
        ]);
        
        echo json_encode([
            'messege' => 'Social Media Configuration has been updated',
            'msgType' => 'success'
        ]);
    }




}
