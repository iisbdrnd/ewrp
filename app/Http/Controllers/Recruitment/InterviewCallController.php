<?php

namespace App\Http\Controllers\Recruitment;
use DB;
use Auth;
use Helper;
use Validator;
use App\Http\Requests;
use App\Model\EwProjects;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Model\EwInterviewCall;
use App\Http\Controllers\Controller;

class InterviewCallController extends Controller
{
    public function interview_call_status_form(Request $request){
    $data['interviewCalls'] = EwInterviewCall::valid()->where('id', $request->id)->first();
    return view('recruitment.interviewCall.interview-call-status', $data);
    }

    public function interview_call_status_update(Request $request){
    EwInterviewCall::where('id', $request->id)->update(['status' => $request->status]);
    $output['messege'] = 'Status has been updated';
    $output['msgType'] = 'success';
    return $output;
    }

    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('recruitment.interviewCall.list', $data);
    }

    public function interviewCallListData(Request $request) {
        $data           = $request->all();
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['ew_project_id']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $data['interviewCalls'] = EwInterviewCall::valid()
            ->where(function($query) use ($search)
            {
                $query->where('ew_project_id', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.interviewCall.listData', $data);
    }
    
     /**
      * 
      *
      * @return Response
      */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $interviewCallId = EwInterviewCall::valid()->get()->pluck('ew_project_id')->all();
        $data['projects']  = EwProjects::valid()->whereNotIn('id', $interviewCallId)->where('status', 1)->get();
        return view('recruitment.interviewCall.create', $data);
    }

    public function add()
    {
        //
    }

    /**
     * 
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        EwInterviewCall::create([
            'ew_project_id' => $request->ew_project_id,
            'status'        => 0
        ]);
            
        $output['messege'] = 'Candidate CV has been created';
        $output['msgType'] = 'success';
        return $output;
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
        $data['editInterviewCalls'] =  EwInterviewCall::valid()->where('id', $id)->first();
        $data['countries']          = DB::table('countries')->get();
        $data['projects']           = EwProjects::valid()->get();
        return view('recruitment.interviewCall.update', $data);
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
       
        $data = [
            'ew_project_id' => $request->ew_project_id   
        ];

        EwInterviewCall::valid()->find($id)->update($data);
        $output['messege'] = 'Company has been updated';
        $output['msgType'] = 'success';
       
        return $output;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {   
        $project = EwInterviewCall::valid()->where('id', $id)->first();
        if($project->status == 1 || $project->status == 2 ){
        return 'You can not delete this project';            
        }else{
           EwInterviewCall::valid()->find($id)->delete();          
        }
            
    }


}
