<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TubOperationalTeam_provider;

class OperationalTeamController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.operationalTeam.list', $data);
    }

    public function operationalTeamListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['departments'] = TubOperationalTeam_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('designation', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.operationalTeam.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.operationalTeam.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'        => 'required',
            'designation' => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'email'       => 'required'
        ]);

        $imageData = $request->thumbnail;
        
        if ($imageData != "") {
            //------------------Thumb Upload-------------------
            $image_name=time().'.png';
            $path='/uploads/managementTeam/';
            $crop_image_data=$request->thumbnail;
            $real_image_data=$request->real_image;
            
            Helper::ThumbcropWithRealImage($image_name,$path,$crop_image_data,$real_image_data);
            //------------------End Thumb Upload-------------------

            if ($validator->passes()) {
                TubOperationalTeam_provider::create([
                    "image"       =>$image_name,
                    'thumbnail'   =>$image_name,
                    'name'        =>$request->name,
                    'designation' =>$request->designation,
                    'address'     =>$request->address,
                    'phone'       =>$request->phone,
                    'email'       =>$request->email
                ]);
                $output['messege'] = 'Management Team has been created';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }else{
            $output['messege'] = 'Image is empty';
            $output['msgType'] = 'danger'; 
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['managementTeam'] = TubOperationalTeam_provider::valid()->find($id);
        return view('provider.eastWest.operationalTeam.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::guard('provider')->user()->project_id;

        $validator = Validator::make($input, [
            'name'        => 'required',
            'designation' => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'email'       => 'required'
        ]);

        $imageData = $request->thumbnail;

        if ($imageData != "") {
            //------------------Thumb Upload-------------------
            $image_name=time().'.png';
            $path='/uploads/managementTeam/';
            $crop_image_data=$request->thumbnail;
            $real_image_data=$request->real_image;
            
            Helper::ThumbcropWithRealImage($image_name,$path,$crop_image_data,$real_image_data);
            //------------------End Thumb Upload-------------------

            if ($validator->passes()) {
                TubOperationalTeam_provider::valid()->find($id)->update([
                    "image"       =>$image_name,
                    'thumbnail'   =>$image_name,
                    'name'        =>$request->name,
                    'designation' =>$request->designation,
                    'address'     =>$request->address,
                    'phone'       =>$request->phone,
                    'email'       =>$request->email
                ]);
                $output['messege'] = 'Management Team has been updated';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }else{
            $output['messege'] = 'Image is empty';
            $output['msgType'] = 'danger'; 
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        TubOperationalTeam_provider::valid()->find($id)->delete();
    }

    public function operationalTeamSorting (Request $request){
        $data['teams'] = TubOperationalTeam_provider::valid()->orderBy('sl_no')->get();
        
        return view('provider.eastWest.operationalTeam.teamSorting', $data);
    }
    public function operationalTeamSortingAction(Request $request){
        $data = $request->all();
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            TubOperationalTeam_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

        $output['messege'] = 'Management team sorting successfully';
        $output['msgType'] = 'success';
        
        echo json_encode($output); 
    }
}
