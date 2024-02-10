<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\HeadOfficeFacility_provider;

class HeadOfficeFacilityController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.headOffice.list', $data);
    }

    public function headOfficeListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['companyPolicies'] = HeadOfficeFacility_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.headOffice.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.headOffice.create', $data);
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
            HeadOfficeFacility_provider::create($input);
            $output['messege'] = 'Head Office Facility has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['chooseUs'] = HeadOfficeFacility_provider::valid()->find($id);
        return view('provider.eastWest.headOffice.update', $data);
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
            HeadOfficeFacility_provider::valid()->find($id)->update($input);
            $output['messege'] = 'Head Office Facility has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        HeadOfficeFacility_provider::valid()->find($id)->delete();
    }
}
