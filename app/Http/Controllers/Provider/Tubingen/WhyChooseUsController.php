<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\WhyChooseUs_provider;

class WhyChooseUsController extends Controller
{
    public function index(Request $request)
    {
        $whyChooseUs = WhyChooseUs_provider::valid()->first();
        if(!empty($whyChooseUs)){
            return self::edit($whyChooseUs->id);
        }else{
            return self::create();
        }
    }

    public function create()
    {
        return view('provider.eastWest.whyChooseUs.create');
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'title'        => 'required',
            'description'     => 'required'
        ]);

        if ($validator->passes()) {
            WhyChooseUs_provider::create($input);
            $output['messege'] = 'Why choose us has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['chooseUs'] = WhyChooseUs_provider::valid()->find($id);
        return view('provider.eastWest.whyChooseUs.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'title'        => 'required',
            'description'  => 'required'
        ]);

        if ($validator->passes()) {
            WhyChooseUs_provider::valid()->find($id)->update($input);
            $output['messege'] = 'Why choose us has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }
}
