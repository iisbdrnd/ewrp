<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TrainingFacility_provider;

class TrainingFacilitiesController extends Controller
{
    public function index(Request $request)
    {
        // $trainingFacilities = TrainingFacility_provider::valid()->get();
        // if(!empty($trainingFacilities)){
        //     return self::facilityEdit($trainingFacilities);
        // }else{
        //     return self::create();
        // }
        $data['inputData'] = $request->all();
        return view('provider.eastWest.trainingFacilities.list', $data);
    }

    public function newsListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['newsList'] = TubNews_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.trainingFacilities.listData', $data);
    }


    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.trainingFacilities.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'description'     => 'required'
        ]);

        if ($validator->passes()) {
            TrainingFacility_provider::create($input);
            $output['messege'] = 'Training Facility has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function facilityEdit($trainingFacilities)
    {
        $data['trainingFacilities'] = $trainingFacilities;
        return view('provider.eastWest.trainingFacilities.update', $data);
    }

    public function update(Request $request)
    {
        $output = array();
        $input = $request->all();
        $rowIds = $request->ids;
        $description = $request->description;

        $validator = Validator::make($input, [
            'description'  => 'required'
        ]);

        if(count($rowIds) > 0){
            foreach($rowIds as $key => $rowId){
                TrainingFacility_provider::valid()->find($rowId)->update(['description' => $description[$key]]);
            }
        }

        $output['messege'] = 'Training Facility has been updated';
        $output['msgType'] = 'success';
     
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        TrainingFacility_provider::valid()->find($id)->delete();
    }
}