<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TubManagementTeam_provider;

class ManagementTeamController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.managementTeam.list', $data);
    }

    public function managementTeamListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['departments'] = TubManagementTeam_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('designation', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('sl_no')
            ->paginate($paginate->perPage);

        return view('provider.eastWest.managementTeam.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.managementTeam.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'        => 'required',
            'designation' => 'required',
            'address'     => 'required',
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
                TubManagementTeam_provider::create([
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
        $data['managementTeam'] = TubManagementTeam_provider::valid()->find($id);
        return view('provider.eastWest.managementTeam.update', $data);
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
                TubManagementTeam_provider::valid()->find($id)->update([
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
        TubManagementTeam_provider::valid()->find($id)->delete();
    }

    public function teamSorting (Request $request){
        $data['teams'] = TubManagementTeam_provider::valid()->orderBy('sl_no')->get();
        
        return view('provider.eastWest.managementTeam.teamSorting', $data);
    }
    public function teamSortingAction(Request $request){
        $data = $request->all();
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            TubManagementTeam_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

        $output['messege'] = 'Management team sorting successfully';
        $output['msgType'] = 'success';
        
        echo json_encode($output); 
    }
}
