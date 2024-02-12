<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwAgency;
use App\Model\EwProjectAgency;
use App\Model\EwCandidatesCV;
use App\Model\EwReferences;
use App\Model\EwProjectCollectableSelection;

class AgencyController extends Controller
{

    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('recruitment.agency.list', $data);
    }

    public function agencyListData(Request $request) 
    {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['agency_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['ewagency'] = EwAgency::valid()
            ->where(function($query) use ($search)
            {
                $query->where('agency_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.agency.listData', $data);
    }

    public function create(Request $request)
    {

        $data['inputData'] = $request->all();
        return view('recruitment.agency.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'agency_name' => 'required',
            'recruiting_licence_no'  => 'required'
        ]);

        if ($validator->passes()) {
            EwAgency::create($input);

            $output['messege'] = 'Trade has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['ewAgency'] = EwAgency::valid()->find($id);
        return view('recruitment.agency.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        $project_id = Auth::user()->get()->project_id;

        $validator = Validator::make($input, [
            'agency_name' => 'required',
            'recruiting_licence_no'  => 'required'
        ]);

        if ($validator->passes()) {
        EwAgency::valid()->find($id)->update($input);
        $output['messege'] = 'Agency has been updated';
        $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        EwAgency::valid()->find($id)->delete();
    }


}