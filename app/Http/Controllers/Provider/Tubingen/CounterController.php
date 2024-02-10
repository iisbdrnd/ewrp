<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Counter_provider;

class CounterController extends Controller
{
    public function index(Request $request)
    {
        return self::edit();
    }

    public function edit()
    {
        $data['counters'] = Counter_provider::valid()->get();
        return view('provider.eastWest.counter.update', $data);
    }

    public function update(Request $request, $a)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'counter' => 'required'
        ]);

        if ($validator->passes()) {
            foreach($request->ids as $key=>$id){
                Counter_provider::valid()->find($id)->update([
                    'counter' => $request->counter[$key]
                ]);
            }
            $output['messege'] = 'Counter has been updated';
            $output['msgType'] = 'success';
        }else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

}
