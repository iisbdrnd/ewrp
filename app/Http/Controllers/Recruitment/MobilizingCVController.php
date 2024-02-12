<?php

namespace App\Http\Controllers\Recruitment;

use DB;
use Auth;
use Helper;
use Validator;
use App\Http\Requests;
use App\Model\EwReferences;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Http\Controllers\Controller;

class MobilizingCVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();

        return view('recruitment.mobilizingCV.list', $data);
    }

    public function showCandidateDetails(Request $request){

        $data['candidates'] = EwCandidatesCV::join('ew_interviews', 
            'ew_interviews.ew_candidatescv_id','=', 'ew_candidatescv.id')
            ->where('id', $request->candidate_id)
            ->first();

        return view('recruitment.mobilizingCV.show-candidate-details', $data); 
    }

    public function mobilizingCandidateListData(Request $request) {

        $data               = $request->all();
        $search             = $request->search;
        $data['access']     = Helper::userPageAccess($request);
        
        $ascDesc            = Helper::ascDesc($data, ['full_name', 'project_name', 'interview_date', 'updated_at']);
        $paginate           = Helper::paginate($data);
        $data['sn']         = $paginate->serial;
        $data['candidates'] = EwCandidatesCV::valid()
            ->where('cv_transferred_status', 1)->where(function($query) use ($search)

            {
                $query->where('full_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('passport_no', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })

            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.mobilizingCV.listData', $data);
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();

        return view('recruitment.mobilizingCV.create', $data);
    }

    public function add()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $output    = array();
        $input     = $request->all();
        $validator = Validator::make($input, [
            'reference_name'  => 'required',
            'reference_phone' => 'required'
        ]);

        if ($validator->passes()) {
            EwReferences::create($input);
            $output['messege'] = 'Reference has been created';
            $output['msgType'] = 'success';
            } else {
            $output  = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['ewReferences'] = EwReferences::valid()->find($id);

        return view('recruitment.mobilizingCV.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $output     = array();
        $input      = $request->all();
        $project_id = Auth::user()->get()->project_id;
        
        $validator  = Validator::make($input, [
            'reference_name'    => 'required',
            'reference_phone'   => 'required'
        ]);

        if ($validator->passes()) {
        EwReferences::valid()->find($id)->update($input);
        $output['messege'] = 'Reference has been updated';
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
        EwReferences::valid()->find($id)->delete();
    }
}
