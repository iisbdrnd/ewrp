<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TubAboutUs_provider;

class AboutUsController extends Controller
{
    public function index(Request $request)
    {
        $aboutUs = TubAboutUs_provider::valid()->first();
        if(!empty($aboutUs)){
            return self::edit($aboutUs->id);
        }else{
            return self::create();
        }
    }

    public function create()
    {
        return view('provider.eastWest.aboutUs.create');
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'title'       => 'required',
            'description' => 'required'
        ]);

        if ($validator->passes()) {
            TubAboutUs_provider::create($input);
            $output['messege'] = 'Description has been created';
            $output['msgType'] = 'success';

            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['aboutUs'] = TubAboutUs_provider::valid()->find($id);
        return view('provider.eastWest.aboutUs.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->passes()) {
        TubAboutUs_provider::valid()->find($id)->update($input);
        $output['messege'] = 'Description has been updated';
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
        TubAboutUs_provider::valid()->find($id)->delete();
    }
}
