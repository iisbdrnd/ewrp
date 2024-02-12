<?php

namespace App\Http\Controllers\Recruitment;

use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use Carbon\Carbon;
use App\Model\Visa;
use App\Model\Medical;
use App\Model\EwTrades;
use App\Model\EwProjects;
use App\Model\EwAgency;
use App\Model\EwProjectAgency;
use App\Model\EwInterview;
use App\Model\EwCandidates;
use App\Model\EwReferences;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Model\EwMobilization;
use App\Model\EwConfiguration;
use App\Model\EwInterviewCall;
use App\Model\EwProjectTrades;
use App\Model\EwVisaJobCategory;
use App\Model\EwReleaseCandidate;
use Illuminate\Routing\Redirector;
use App\Model\MobilizationActivity;
use App\Http\Controllers\Controller;
use App\Model\EwMobilizeProjectSetup;
use App\Model\EwMobilizationMasterTable;

class MobilizationController extends Controller
{

    protected $projectId;
    protected $candidateId;

    public function __construct(Request $request, Redirector $redirect)
    {
        $this->projectId = $request->projectId;
        $this->candidateId = $request->candidateId;
        $this->projectCountryId = $request->projectCountryId;
    }

    /**
     * Accounts transfering candidate list method
     * @param $projectId
     * Getting all candidate from a project
     */
    public function accountsTransferCandidateList(Request $request)
    {
        $data['projectId'] = $this->projectId;
        $data['inputData'] = $request->all();
        return view('recruitment.mobilization.accountsTransferCandidateList', $data);
    }

    /**
     * Accounts Transfer Candidate List Data by projectId
     * @param $projectId
     */

    public function accountsTransferCandidateData(Request $request)
    {
        $request->cv_transfer_status;
        $data = $request->all();
        $cv_transfer_status = (!empty($request->cv_transfer_status) ? $request->cv_transfer_status : 0);

        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['full_name', 'updated_at']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $data['candidateDetails'] = EwCandidatesCV::valid()
            ->where('ew_project_id', $this->projectId)
            ->where(function ($query) use ($cv_transfer_status) {
                if (!empty($cv_transfer_status)) {

                    switch ($cv_transfer_status) {
                        case 1:
                            $query->where('candidate_status', 1);
                            break;
                        case 2:
                            $query->where('candidate_status', 0);
                            $query->orWhere('candidate_status', null);
                            break;
                        default:
                            $query->where('candidate_status', 0)
                                ->orWhere('candidate_status', 1);
                            break; 
                    }

                }
            })
            ->where(function ($query) use ($search) {
                $query->where('full_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('education', 'LIKE', '%' . $search . '%')
                    ->orWhere('cv_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('father_name', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        $data['cv_transfer_status'] = $cv_transfer_status;

        return view('recruitment.mobilization.accountsTransferCandidateListData', $data);
    }

    public function updateSelectedTradeForm(Request $request)
    {
        $data['projectId']   = $this->projectId;
        $data['candidateId'] = $this->candidateId;

        $data['trades']      = EwTrades::join('ew_project_trades', 'ew_project_trades.trade_id', ' = ', 'ew_trades.id')
        ->where('ew_project_trades.ew_project_id', ' = ', $this->projectId)
        ->select('ew_project_trades.trade_id', 'ew_trades.id', 'ew_trades.trade_name')
        ->get();

        return view('recruitment.mobilization.updateSelectedTradeForm', $data);
    }

    public function updateSelectedTrade(Request $request)
    {
        EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->where('id', $this->candidateId)
            ->update([
            'selected_trade' => $request->selected_trade,
        ]);
        $output['messege'] = 'Selection trade has been updated';
        $output['msgType'] = 'success';
        return $output;
    }

    /**
     * Close & Complete Button mobilization [Final State]
     * Mobilization Name: Close
     * Jquery Event onclick="closeTab()"
     * URL adding with to "Complete" button
     * After Click "Complete" button open candidate create form
     *
     */

    public function candidateForm(Request $request)
    {
        $data['projectId']     = $this->projectId;
        $data['candidateId']   = $this->candidateId;
        $data['inputData']     = $request->all();
        $data['ewProjects']    = EwProjects::valid()->get();
        $data['ewTrades']      = EwTrades::valid()->get();
        $data['ewReferences']  = EwReferences::valid()->get();

        $data['candidateInfo'] = EwCandidatesCV::valid()
            ->where('ew_project_id', $this->projectId)
            ->where('id', $this->candidateId)
            ->first();

        return view('recruitment.mobilization.create-candidate', $data);
    }

    /**
     *
     */
    public function candidateFormStore(Request $request)
    {
        $candidateStatus = EwCandidatesCV::valid()->where('id', $this->candidateId)->where('ew_project_id', $this->projectId)->first();

        if ($candidateStatus->candidate_status != 1) {

            // DB::beginTransaction();
            // return $candidateStatus->selected_trade;
            $output         = array();
            $getCandidateId = Helper::getCandidateId();

            $candidates = EwCandidates::create([
                "candidate_id"       => $getCandidateId,
                "ew_candidatescv_id" => $this->candidateId,
                "ew_project_id"      => $this->projectId,
                "trade_id"           => $candidateStatus->selected_trade,
                "reference_id"       => $candidateStatus->reference_id,
                "collectable_status" => 0,
                "candidate_name"     => $candidateStatus->full_name,
                "father_name"        => $candidateStatus->father_name,
                "passport_number"    => $candidateStatus->passport_no,
                "flight_status"      => 0,
            ]);

            EwCandidatesCV::where('id', $this->candidateId)->update([
                'candidate_id'     => $candidates->candidate_id,
                'candidate_status' => 1,
            ]);

            $output['messege'] = 'Candidate information has been created';
            $output['msgType'] = 'success';

        } else {

            $output['messege'] = 'This candidate already created!';
            $output['msgType'] = 'danger';

        }
        return response()->json($output);
// DB::commit();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();

        return view('recruitment.mobilization.list', $data);
    }

    public function mobilizationProjectListData(Request $request)
    {
        $data           = $request->all();
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['project_name','coordinator_name', 'updated_at']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $projects = EwProjects::leftJoin('users', 'ew_projects.coordinator', '=', 'users.id')
        ->select('ew_projects.*', 'users.name as coordinator_name')
        ->where('ew_projects.valid', 1)
        ->where('ew_projects.status', 1)
        ->where(function ($query) use ($search) {
            $query->where('ew_projects.project_name', 'LIKE', '%' . $search . '%')
            ->orWhere('users.name', 'LIKE', '%' . $search . '%')
            ->orWhere('ew_projects.updated_at', 'LIKE', '%' . $search . '%');
        })
        ->orderBy($ascDesc[0], $ascDesc[1])
        ->paginate($paginate->perPage);

        $data['ewProjects'] = $projects;

/*        foreach ($projects as $ewProject) {
            $ewProject->agency = EwProjectAgency::leftJoin('ew_agency','ew_project_agency.agency_id', '=', 'ew_agency.id')
                               ->where('ew_project_agency.ew_project_id',$ewProject->id)
                               ->where('ew_project_agency.valid',1)
                               ->select('ew_agency.agency_name','ew_project_agency.quantity')
                               ->get();

            $ewProject->trades = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
                ->select('ew_project_trades.trade_qty', 'ew_trades.trade_name')
                ->where('ew_project_trades.ew_project_id', $ewProject->id)
                ->where('ew_project_trades.valid', 1)
                ->get();
        }
*/
        return view('recruitment.mobilization.listData', $data);
    }

    public function mobilizationRoom(Request $request)
    {
        $data['mobilizeId']       = $request->mobilization;
        $data['projectId']        = $projectId = $this->projectId;
        $data['projectCountryId'] = $this->projectCountryId;
        $data['inputData']        = $request->all();

        $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
            ->get()
            ->pluck('ew_candidatescv_id');

        $candidatePasses = EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->where('result', 1)
            ->whereNotIn('id', $candidateIds)->get();

        foreach ($candidatePasses as $candidatePass) {

            EwMobilizationMasterTable::create([
                'ew_project_id'      => $projectId,
                'ew_candidatescv_id' => $candidatePass->id,
                'project_country_id' => $candidatePass->project_country_id,
            ]);

        }

        if (@Helper::checkAssignuser($this->projectId) == "notAllowed") {

            return "You have no access!";

        } else {

            return view('recruitment.mobilization.mobilizationRoom', $data);

        }
    }

    public function mobilizationRoomData(Request $request)
    {
        $data['projectId']        = $projectId = $this->projectId;
        $data['projectCountryId'] = $this->projectCountryId;
        $data                     = $request->all();
        $search                   = $request->search;
        $data['access']           = Helper:: userPageAccess($request);
        $ascDesc                  = Helper:: ascDesc($data, ['project_name', 'updated_at']);
        $paginate                 = Helper:: paginate($data);
        $data['sn']               = $paginate->serial;

        $mobilization = EwConfiguration::valid()
            ->where('ew_project_id', $this->projectId)
            ->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('updated_at', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        $totalCandidateIds = EwMobilizationMasterTable::valid()
            ->where('ew_project_id', $projectId)
            ->get()
            ->pluck('ew_candidatescv_id');

        $data['masterData'] = EwMobilizationMasterTable::valid()
            ->where('ew_project_id', $projectId)
            ->first();

        $data['total_candidates'] = $total_candidates =  EwCandidatesCV::valid()
            ->where('ew_project_id', $this->projectId)
            ->where('result', 1)
            ->where('approved_status', 0)
            ->count();

        $data['total_selected'] = EwCandidatesCV::valid()
            ->where('ew_project_id', $this->projectId)
            ->where('result', 1)
            ->where('approved_status', 1)
            ->count() + $total_candidates;

        $data['approvedCandidates'] = EwCandidatesCV::valid()
            ->where('ew_project_id', $this->projectId)
            ->where('result', 1)
            ->where('approved_status', 1)
            ->count();

        $data['finzalizing'] = EwMobilizationMasterTable::valid()
            ->where('flight_briefing_completed', 1)
            ->where('ew_project_id', $projectId)
            
            ->where('flight_completed', 1)
            ->count();

        $data['mobilizations'] = $mobilization;

        return view('recruitment.mobilization.mobilizationRoomData', $data);
    }
    /**
     * Candidate List
     * url: mobilization/mobilizationRoomCandidateList/{projectId}
     * Candidate List open after click on mobilization from Mobilization Room
     *
     */
    public function mobilizationRoomCandidateList(Request $request)
    {
        $data['projectId']        = $this->projectId;
        $data['projectCountryId'] = $this->projectCountryId;

        return view('recruitment.mobilization.mobilizationRoomCandidateList', $data);
    }

    public function mobilizeModalViewForm()
    {
        return view('recruitment.mobilization.mobilizeModalViewForm');
    }

    public function mobilizeModalActivityViewFrom()
    {
        $data['mobilizeLists'] = self::mobilizationList();
        return view('recruitment.mobilization.mobilizeModalActivityViewFrom', $data);
    }

    public function mobilizeActivity(Request $request)
    {

        $data['projectLists'] = self::mobilizationProjectListData($request);
        return view('recruitment.mobilization.mobilizeActivity', $data);
    }

    public function mobilizationActivityRoomCandidateList(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['projectId'] = $this->projectId;

        return view('recruitment.mobilization.mobilizationActivityRoomCandidateList', $data);
    }

    public function mobilizationActivityRoomCandidateListData(Request $request)
    {
        $data['projectId']     = $projectId       = $this->projectId;
        $data                  = $request->all();
        $search                = $request->search;
        $data['access']        = Helper::userPageAccess($request);
        $ascDesc               = Helper::ascDesc($data, ['full_name', 'updated_at']);
        $paginate              = Helper::paginate($data);
        $data['sn']            = $paginate->serial;
        $data['jobCategories'] = EwVisaJobCategory::valid()->get();
        $data['mobilizeLists'] = self::mobilizationList();

        $candidateLists = EwCandidatesCV::valid()->where('ew_project_id', $this->projectId)->where(function ($query) use ($search) {
            $query->where('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('updated_at', 'LIKE', '%' . $search . '%');
        })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
        
        $data['candidateLists'] = $candidateLists;

        return view('recruitment.mobilization.mobilizationActivityRoomCandidateListData', $data);
    }

    public function candidateApproveStatus(Request $request)
    {
        EwCandidatesCV::where('id', $request->candidateId)
            ->where('ew_project_id', $request->projectId)
            ->update([
            'approved_status' => 1,
            'approved_date'   => Carbon::now()
            ]);

        $output['messege'] = Helper::single_candidate($request->candidateId)->full_name . ' is approved!';
        $output['msgType'] = 'success';

        return $output;
    }


    /**
     * After click on Restore button from candidate list the candidate is restored to the 
     * selection list. Candidate list is loaded after click on mobilization box
     * Ajax call catch & return these two param
     * @param  $projectId
     * @param $candidateId
     * @method  restoreCandidate()
     */
    
    public function restoreCandidate(Request $request)
    {
        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->update([
            'completeness'                 => "",
            'gamca_id'                     => "",
            'gamca_gone_date'              => "",
            'gamca_status'                 => "",
            'gamca_completed'              => "",
            'medical_id'                   => "",
            'medical_gone_date'            => "",
            'medical_name'                 => "",
            'medical_code'                 => "",
            'medical_report_date'          => "",
            'medical_actual_status'        => "",
            'medical_completed'            => "",
            'medical_online_id'            => "",
            'medical_online_name'          => "",
            'medical_online_code'          => "",
            'medical_online_date'          => "",
            'medical_online_status'        => "",
            'medical_online_completed'     => "",
            'medical_self_id'              => "",
            'medical_self_name'            => "",
            'medical_self_code'            => "",
            'medical_self_date'            => "",
            'medical_self_status'          => "",
            'medical_self_completed'       => "",
            'remedical_id'                 => "",
            'remedical_name'               => "",
            'remedical_date'               => "",
            'remedical_code'               => "",
            'remedical_actual_status'      => "",
            'remedical_status'             => "",
            'remedical_completed'          => "",
            'medical_call_id'              => "",
            'medical_call_status'          => "",
            'medical_call_completed'       => "",
            'medical_call_date'            => "",
            'medical_sent_id'              => "",
            'medical_sent_date'            => "",
            'medical_sent_status'          => "",
            'medical_sent_completed'       => "",
            'medical_slip_id'              => "",
            'medical_slip_center'          => "",
            'medical_slip_no'              => "",
            'medical_slip_date'            => "",
            'medical_slip_status'          => "",
            'medical_slip_completed'       => "",
            'document_sent_id'             => "",
            'document_sent_date'           => "",
            'document_sent_status'         => "",
            'document_sent_completed'      => "",
            'fit_card_id'                  => "",
            'fit_card_received_date'       => "",
            'fit_card_received_status'     => "",
            'fit_card_received_completed'  => "",
            'mofa_id'                      => "",
            'mofa_date'                    => "",
            'mofa_status'                  => "",
            'mofa_completed'               => "",
            'visa_document_sent_id'        => "",
            'visa_document_sent_date'      => "",
            'visa_document_sent_status'    => "",
            'visa_document_sent_completed' => "",
            'embassy_submission_id'        => "",
            'embassy_submission_date'      => "",
            'embassy_submission_status'    => "",
            'embassy_submission_completed' => "",
            'visa_online_id'               => "",
            'visa_online_date'             => "",
            'visa_online_status_code'      => "",
            'visa_online_job_category_id'  => "",
            'visa_online_expiry_date'      => "",
            'visa_online_actual_status'    => "",
            'visa_online_completed'        => "",
            'visa_id'                      => "",
            'visa_issued_date'             => "",
            'visa_expiry_date'             => "",
            'visa_actual_status'           => "",
            'visa_completed'               => "",
            'visa_print_id'                => "",
            'visa_print_date'              => "",
            'visa_no'                      => "",
            'visa_print_status'            => "",
            'visa_print_completed'         => "",
            'visa_attached_id'             => "",
            'visa_attached_date'           => "",
            'visa_attached_no'             => "",
            'visa_attached_status'         => "",
            'visa_attached_completed'      => "",
            'pcc_id'                       => "",
            'pcc_serial_number'            => "",
            'pcc_date'                     => "",
            'pcc_status'                   => "",
            'pcc_completed'                => "",
            'pcc_received_id'              => "",
            'pcc_received_date'            => "",
            'pcc_received_status'          => "",
            'pcc_received_completed'       => "",
            'gttc_id'                      => "",
            'gttc_serial_number'           => "",
            'gttc_date'                    => "",
            'gttc_status'                  => "",
            'gttc_completed'               => "",
            'gttc_received_id'             => "",
            'gttc_received_date'           => "",
            'gttc_received_status'         => "",
            'gttc_received_completed'      => "",
            'fingerprint_status_id'        => "",
            'fingerprint_date'             => "",
            'fingerprint_status'           => "",
            'fingerprint_completed'        => "",
            'bmet_id'                      => "",
            'bmet_date'                    => "",
            'bmet_status'                  => "",
            'bmet_completed'               => "",
            'smartcard_id'                 => "",
            'smartcard_date'               => "",
            'smartcard_status'             => "",
            'smartcard_completed'          => "",
            'pta_request_id'               => "",
            'pta_request_date'             => "",
            'pta_request_status'           => "",
            'pta_request_completed'        => "",
            'pta_received_id'              => "",
            'flight_date'                  => "",
            'flight_no'                    => "",
            'flight_time'                  => "",
            'transit_place'                => "",
            'pta_received_date'            => "",
            'pta_received_status'          => "",
            'pta_received_completed'       => "",
            'qvc_appointment_id'           => "",
            'qvc_appointment_date'         => "",
            'qvc_appointment_status'       => "",
            'qvc_appointment_completed'    => "",
            'flight_id'                    => "",
            'flight_status'                => "",
            'flight_completed'             => "",
            'flight_briefing_id'           => "",
            'flight_briefing_date'         => "",
            'flight_briefing_status'       => "",
            'flight_briefing_completed'    => "",
            'total_completed'    => "",
            'created_at'                   => "",
            'updated_at'                   => "",
            ]);

        EwCandidatesCV::where('ew_project_id', $this->projectId)
            ->where('id', $this->candidateId)
            ->update(['approved_status' => 0]);

        $output['messege'] = Helper::single_candidate($request->candidateId)->full_name . ' is gone to selection list!';
        $output['msgType'] = 'success';

        return $output;

    }

    public function releaseCandidate(Request $request)
    {
       
       $check = EwReleaseCandidate::where('ew_candidatescv_id', $this->candidateId)
       ->where('ew_project_id', $this->projectId)
       ->where('project_country_id')->exists();
       
       if ($check == true) {

       } else {
           EwReleaseCandidate::create([
            'ew_candidatescv_id' => $this->candidateId,
            'ew_project_id'      => $this->projectId,
            'project_country_id' => $this->projectCountryId,
        ]);
       }
       
        EwCandidatescv::where('id', $this->candidateId)->update([
            'ew_project_id'         => 0,
            'cv_transferred_status' => 0,
            'result'                => 0,
        ]);

        EwInterview::where('ew_candidatescv_id', $this->candidateId)
        ->update([
        'ew_project_id' => 0,
        'interview_selected_status' => 0
        ]);
        $output['messege'] = 'Candidate released successfully from this project!';
        $output['msgType'] = 'success';
        return $output;

    }

    /**
     * RETURN MODAL INPUT ELEMENT VALUE
     * After clicking on mobilization box a candidate list is loaded
     * After loaded candidate list you can see incompleted candidate list & another two button
     * completed & wip
     * when you will click on save or edit mobilization button from candidate list then a modal 
     * will be opened at that time you can see input field where date or anyother value will be
     *  appeared that value returned from this 
     * @method  getMobilizationData()
     * You can check mobilizationRoomCandidateData blade, where javascript machanism was done for
     * viewing those input field value in modal
     * @param $projectId
     * @param $candidateId
     * @param $mobilizeId
     */

    public function getMobilizationData(Request $request)
    {
        switch ($request->mobilizeId) {
            case "candidates":
            $data = 0;
            break;
            case 1:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('medical_id', $request->mobilizeId)
                    ->select('medical_name', 'medical_status_date', 'medical_gone_date', 'medical_code', 'medical_actual_status','medical_expire_date')
                    ->first();
                break;
            case 2:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('medical_online_id', $request->mobilizeId)
                    ->select('medical_online_name', 'medical_online_date', 'medical_online_code', 'medical_online_status')
                    ->first();
                break;
            case 3:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('medical_self_id', $request->mobilizeId)
                    ->select('medical_self_name', 'medical_self_code', 'medical_self_date', 'medical_self_status')
                    ->first();
                break;
            case 4:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('fit_card_id', $request->mobilizeId)
                    ->select('fit_card_received_date')
                    ->first();
                break;
            case 5:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('mofa_id', $request->mobilizeId)
                    ->select('mofa_date')
                    ->first();
                break;
            case 6:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('visa_document_sent_id', $request->mobilizeId)
                    ->select('visa_document_sent_date')
                    ->first();
                break;
            case 7:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('embassy_submission_id', $request->mobilizeId)
                    ->select('embassy_submission_date')
                    ->first();
                break;
            case 8:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('embassy_submission_id', $request->mobilizeId)
                    ->select('embassy_submission_date')
                    ->first();
                break;
            case 9:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('visa_online_id', $request->mobilizeId)
                    ->select('visa_online_date', 'visa_online_status_code', 'visa_online_expiry_date', 'visa_online_actual_status')
                    ->first();
                break;
            case 10:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('visa_print_id', $request->mobilizeId)
                    ->select('visa_print_date', 'visa_no','visa_print_expiry_date')
                    ->first();
                break;
            case 11:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('visa_attached_id', $request->mobilizeId)
                    ->select('visa_attached_date', 'visa_attached_no','visa_attach_expiry_date')
                    ->first();
                break;

            case 12:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('pcc_id', $request->mobilizeId)
                    ->select('pcc_serial_number', 'pcc_date')
                    ->first();
                break;
            case 13:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('gttc_id', $request->mobilizeId)
                    ->select('gttc_serial_number','training_center_name', 'training_start_date', 'gttc_date','gttc_completed')
                    ->first();
                break;
            case 14:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('fingerprint_status_id', $request->mobilizeId)
                    ->select('fingerprint_date')
                    ->first();
                break;
            case 15:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('bmet_id', $request->mobilizeId)
                    ->select('bmet_date')
                    ->first();
                break;
            case 16:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('gamca_id', $request->mobilizeId)
                    ->select('gamca_gone_date')
                    ->first();
                break;
            case 17:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('medical_slip_id', $request->mobilizeId)
                    ->select('medical_slip_no', 'medical_slip_center', 'medical_slip_date', 'medical_slip_status')
                    ->first();
                break;
            case 18:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('document_sent_id', $request->mobilizeId)
                    ->select('document_sent_date')
                    ->first();
                break;
            case 19:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('remedical_id', $request->mobilizeId)
                    ->select('remedical_date', 'remedical_name', 'remedical_actual_status')->first();
                break;
            case 20:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('pcc_received_id', $request->mobilizeId)
                    ->select('pcc_received_date')
                    ->first();
                break;
            case 21:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('gttc_received_id', $request->mobilizeId)
                    ->select('gttc_received_date','gttc_received_status')
                    ->first();
                break;
            case 22:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('smartcard_id', $request->mobilizeId)
                    ->select('smartcard_date')
                    ->first();
                break;
            case 23:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('pta_request_id', $request->mobilizeId)
                    ->select('pta_request_date')
                    ->first();
                break;
            case 24:
                $ptaRow  = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                ->where('ew_candidatescv_id', $request->candidateId)
                ->where('pta_request_completed', 1)
                ->select('pta_received_id', 'pta_request_completed')
                ->first();

                if($ptaRow->pta_received_id == 0){
                    $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('pta_request_completed', 1)
                    ->where('pta_received_id', 0)
                    ->select('pta_request_date')
                    ->first();
                }else{
                    $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('pta_received_id', $request->mobilizeId)
                    ->select('pta_received_date', 'flight_date', 'pta_request_date', 'pta_received_status', 'flight_no', 'flight_time', 'transit_place')
                    ->first();
                }
                break;
            case 25:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('flight_id', $request->mobilizeId)
                    ->select('flight_status')
                    ->first();
                break;
            case 26:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('flight_briefing_id', $request->mobilizeId)
                    ->select('flight_briefing_date', 'flight_date')
                    ->first();
                break;
            case 27:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('qvc_appointment_id', $request->mobilizeId)
                    ->select('qvc_appointment_date')
                    ->first();
                break;
            case 28:
                $data = EwCandidatesCV::valid()
                ->where('ew_project_id', $request->projectId)
                ->where('id', $request->candidateId)
                ->select('approved_status', 'approved_date')
                ->first();

                break;
            case 29:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('medical_call_id', $request->mobilizeId)
                    ->select('medical_call_date', 'gttc_serial_number', 'gttc_date', 'pcc_serial_number', 'pcc_date')
                    ->first();
                break;
            case 30:
                $data = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                    ->where('ew_candidatescv_id', $request->candidateId)
                    ->where('medical_sent_id', $request->mobilizeId)
                    ->select('medical_sent_date', 'medical_sent_status', 'medical_slip_center', 'medical_slip_no', 'medical_slip_date', 'medical_slip_status')
                    ->first();
                break;
        }

        return response()->json($data);
    }
    
    public static function getMobilizeDependedData(){

    }
    public function getPrevMobilizationDate(Request $request)
    {
        // $data = EwMobilizeProjectSetup::where( 'project_country_id', 185)->where('mobilize_id', 1)->get();          
        
    //    echo $this->projectCountryId;
    switch ($request->mobilizeId) {
        case 1:
            # code...30
            if ($this->projectCountryId == 185) {
            $data = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('medical_sent_id', 30)
            ->where('medical_sent_completed', 1)
            ->first()->medical_sent_date;
            }
        
            break;
        case 2:
            # code... 7
            break;
        case 3:
            # code...
            break;
        case 4:
            # code...
            break;
        case 5:
            # code...
            break;
        case 6:
            # code...
            break;
        case 7:
            # code... 11
            break;
        case 8:
            # code...
            break;
        case 9:
            # code...
            break;
        case 10:
            # code...
            break;
        case 11:
            # code... 14
            break;
        case 12:
        case 13:
        case 17:
        case 1:
            # code... 30
            break;
        case 14:
            # code... 15
            break;
        case 15:
            # code... 22
            break;
        case 22:
            # code... 23
            break;
        case 23:
            # code... 24
            break;
        case 24:
            # code... 26
            break;
        case 26:
            # code... 25
            break;
        default:
            # code...
            break;
    }  
            
    return  response()->json([Carbon::parse( $data)->format('m-d-Y')]);
        

    }

    public function wip_status(Request $request)
    {

        if ($request->status == 1){
            EwCandidatesCV::where('ew_project_id', $this->projectId)
                ->where('id', $this->candidateId)->update(['wip_status' => 0]);
            return response()->json(['data' => 0,'message' => @Helper::single_candidate($this->candidateId)->full_name." is activated"]);    
        } else {
            EwCandidatesCV::where('ew_project_id', $this->projectId)
                ->where('id', $this->candidateId)->update(['wip_status' => 1]);

            return response()->json(['data' => 1,'message' => @Helper::single_candidate($this->candidateId)->full_name . " is declined"]);  
        }
       
    }

    /**
     * KSA    = 185
     * QATAR  = 180
     */
    public function mobilizationRoomCandidateData(Request $request)
    {
        $data['projectId']     = $projectId               = $this->projectId;
        $data['mobilizeId']    = $mobilizeId              = $request->mobilizeId;
        $data                  = $request->all();
        $search                = $request->search;
        $all_data                = $request->all_data;
        $data['access']        = Helper::userPageAccess($request);
        $ascDesc               = Helper::ascDesc($data, ['project_name', 'updated_at']);
        $paginate              = Helper::paginate($data);
        $data['sn']            = $paginate->serial;
        $data['jobCategories'] = EwVisaJobCategory::valid()->get();
        $search_val = $request->search_data;



        $total_flight        = EwMobilizationMasterTable::where('ew_project_id', $projectId)
            ->where('flight_completed', 1)
            ->get()
            ->pluck('ew_candidatescv_id');

        $not_pass_candidates = EwCandidatescv::valid()
            ->where('ew_project_id', $this->projectId)
            ->where('approved_status', 1)
            ->whereNotIn('result', [1])
            ->get()
            ->pluck('id');

        $mobilizeDependency = @Helper::mobilize_dependency($this->projectCountryId, $mobilizeId);
        $objectQuery = @Helper::single_mobilization( $mobilizeDependency->mobilize_depended_on_id)->mobilize_object;
        $dependencyObject = explode(",", $objectQuery);   
        
        // dd($dependencyObject);

        switch ($mobilizeId) {
            case 'candidates':
                $candidateIds = EwCandidatesCV::valid()
                    ->where('ew_project_id', $projectId)
                    ->where('result', 1)
                    ->get()
                    ->pluck('id');
   
                $data['mobilize_stage'] = EwMobilizationMasterTable::valid() 
                    ->where('ew_project_id', $projectId)
                    ->whereIn('ew_candidatescv_id', $candidateIds)
                    ->select('total_completed')
                    ->get();//->pluck('ew_candidatescv_id');

                $mobilization_date = EwInterview::where('ew_project_id', $this->projectId)
                            ->whereIn('ew_candidatescv_id', $candidateIds)
                            ->get()
                            ->pluck('selection_date'); 
      

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE) 
                        {
                            $mobilization_date = EwCandidatesCV::leftjoin('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_interviews.selection_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_interviews.ew_project_id', $this->projectId)
                                    ->where(function($query) use ($search_val)
                                    {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%');
                                    })
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('selection_date');

                            $candidateDetail = EwCandidatesCV::leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                                    ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                                    ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                        'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date', 
                                        'ew_candidatescv.passport_status', 'ew_candidatescv.wip_status','ew_interviews.selection_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where(function($query) use ($search_val)
                                    {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE',$search_val)
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%');
                                    })
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->paginate(100);
                        }
                        else
                        {

                            $mobilization_date = EwInterview::where('ew_project_id', $this->projectId)
                                ->whereIn('ew_candidatescv_id', $candidateIds)
                                ->where('selection_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                ->get()
                                ->pluck('selection_date');    

                            $candidateDetail = EwCandidatesCV::leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                                    ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                                    ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                        'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date', 
                                        'ew_candidatescv.passport_status', 'ew_candidatescv.wip_status','ew_interviews.selection_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where(function($query) use ($search_val)
                                    {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE',$search_val)
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_interviews.selection_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                    })
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->paginate(100);
                                    
                        }
     
                    }
                }

                $data['depMobilizeId'] = false;
                break;
            case 'finalizing':
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('flight_briefing_completed', 1)
                    ->where('flight_completed', 1)
                    ->get()
                    ->pluck('ew_candidatescv_id');
                $mobilization_date = EwInterview::where('ew_project_id', $this->projectId)
                    ->whereIn('ew_candidatescv_id', $candidateIds)
                    ->get()
                    ->pluck('selection_date');
                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE) {
                            $mobilization_date = EwCandidatesCV::leftjoin('ew_interviews', 'ew_candidatescv.id', '=', 'ew_interviews.ew_candidatescv_id')
                                ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_interviews.selection_date')
                                ->where('ew_candidatescv.valid',1)
                                ->where('ew_interviews.ew_project_id', $this->projectId)
                                ->where(function($query) use ($search_val)
                                {
                                    $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                })
                                ->where('ew_candidatescv.result', 1)
                                ->whereIn('ew_interviews.ew_candidatescv_id', $candidateIds)
                                ->get()
                                ->pluck('selection_date');
                        }else{
                            $mobilization_date = EwInterview::where('ew_project_id', $this->projectId)
                                ->whereIn('ew_candidatescv_id', $candidateIds)
                                ->where('selection_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                ->get()
                                ->pluck('selection_date');    
                        }

                        $candidateDetail = EwCandidatesCV::leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                        ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                        ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                            'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date', 
                            'ew_candidatescv.passport_status', 'ew_candidatescv.wip_status','ew_interviews.selection_date')
                        ->where('ew_candidatescv.valid',1)
                        ->where(function($query) use ($search_val)
                        {
                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_interviews.selection_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                        })
                        ->where('ew_candidatescv.result', 1)
                        ->whereIn('ew_candidatescv.id', $candidateIds)
                        ->paginate(100);
                    }
                }
                $data['depMobilizeId'] = false;    
                break;
            case 1:
                    // return $request->data;
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('medical_slip_completed', 1)
                            ->where(function ($query) use ($request, $total_flight) {
                                switch ($request->data) {
                                    case 1:
                                         $query->whereIn('medical_actual_status', array(1, 4));
                                        break;
                                    case 3:
                                         $query->whereIn('medical_actual_status', array(1, 4))
                                        ->whereNotIn('ew_candidatescv_id', $total_flight);
                                        break;
                                    default:
                                        $query->whereNotIn('medical_actual_status', array(1, 4));
                                        break;
                                }
                            })
                            ->get()->pluck('ew_candidatescv_id');

                if ($this->projectCountryId == 180 || $this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where( $dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where( $dependencyObject[2], 1)
                        ->get()
                        ->pluck( $dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                    
                if ($this->projectCountryId == 180 || $this->projectCountryId == 46) {

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('medical_slip_completed', 1)
                            ->whereNotIn('medical_actual_status', array(1, 4))
                            ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                            ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('medical_slip_completed', 1)
                            ->whereIn('medical_actual_status', array(1, 4))
                            ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                            ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_slip_completed', 1)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('medical_slip_completed', 1)
                            ->where(function ($query) use ($request, $total_flight) {
                                switch ($request->data) {
                                    case 1:
                                         $query->whereIn('medical_actual_status', array(1, 4));
                                        break;
                                    case 3:
                                         $query->whereIn('medical_actual_status', array(1, 4))
                                        ->whereNotIn('ew_candidatescv_id', $total_flight);
                                        break;
                                    default:
                                        $query->whereNotIn('medical_actual_status', array(1, 4));
                                        break;
                                }
                            })
                            ->get()
                            ->pluck('medical_gone_date');     

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                            $prevDate = $prevDate;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE) 
                            {
                               $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_gone_date')
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where('ew_mobilization_master_tables.medical_slip_completed', 1)
                                    ->where('ew_candidatescv.valid',1)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                        case 1:
                                            $query->whereIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4));
                                            break;
                                        case 3:
                                            $query->whereIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4))
                                            ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->whereNotIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4));
                                            break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('medical_gone_date');  
                            }
                            else
                            {
                               $mobilization_date =  EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_slip_completed', 1)
                                    ->where('medical_gone_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function ($query) use ($request, $total_flight) {
                                        switch ($request->data) {
                                            case 1:
                                                 $query->whereIn('medical_actual_status', array(1, 4));
                                                break;
                                            case 3:
                                                 $query->whereIn('medical_actual_status', array(1, 4))
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->whereNotIn('medical_actual_status', array(1, 4));
                                                break;
                                        }
                                    })
                                    ->get()
                                    ->pluck('medical_gone_date'); 
                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.medical_gone_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                } // end 180

                if ($this->projectCountryId == 185 || $this->projectCountryId == 172) 
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_slip_completed', 1)
                        ->where(function ($query) use ($request, $total_flight) {

                        switch($request->data){
                            case 1:
                                $query->whereIn('medical_actual_status', array(1, 4));
                                break;
                            case 3:
                                $query->whereIn('medical_actual_status', array(1, 4))
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->whereNotIn('medical_actual_status', array(1, 4));
                                break;
                            }
                        })
                        ->get()->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_slip_completed', 1)
                        ->whereNotIn('medical_actual_status', array(1, 4))
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_slip_completed', 1)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_slip_completed', 1)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                   $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_slip_completed', 1)
                        ->where(function ($query) use ($request, $total_flight) {
                        switch($request->data){
                            case 1:
                                $query->whereIn('medical_actual_status', array(1, 4));
                                break;
                            case 3:
                                $query->whereIn('medical_actual_status', array(1, 4))
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->whereNotIn('medical_actual_status', array(1, 4));
                                break;
                            }
                        })
                        ->get()
                        ->pluck('medical_gone_date');


                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                            $prevDate = $prevDate;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_gone_date')
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where('ew_mobilization_master_tables.medical_slip_completed', 1)
                                        ->where('ew_candidatescv.valid',1)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                            case 1:
                                                $query->whereIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4));
                                                break;
                                            case 3:
                                                $query->whereIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4))
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->whereNotIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4));
                                                break;
                                            }

                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('medical_gone_date');
                                        
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::valid()
                                    ->where('ew_project_id', $projectId)
                                    ->where('medical_slip_completed', 1)
                                    ->where('medical_gone_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function ($query) use ($request, $total_flight) {
                                    switch($request->data){
                                        case 1:
                                            $query->whereIn('medical_actual_status', array(1, 4));
                                            break;
                                        case 3:
                                            $query->whereIn('medical_actual_status', array(1, 4))
                                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->whereNotIn('medical_actual_status', array(1, 4));
                                            break;
                                        }
                                    })
                                    ->get()
                                    ->pluck('medical_gone_date'); 
                            } 

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.medical_gone_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
     
                        }

                    }                    

                }  // end 185

                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                    ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                    ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                    ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                        'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date', 
                        'ew_candidatescv.passport_status', 'ew_candidatescv.wip_status','ew_mobilization_master_tables.medical_gone_date')
                    ->where('ew_candidatescv.valid',1)
                    ->where(function($query) use ($search_val)
                    {
                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_mobilization_master_tables.medical_gone_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                    })
                    ->where('ew_candidatescv.result', 1)
                    ->whereIn('ew_candidatescv.id', $candidateIds)
                    ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                    ->paginate(100);

                $data['medical_status'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_slip_completed', 1)
                    ->whereIn('ew_candidatescv_id', $candidateIds)
                    ->where(function ($query) use ($request, $total_flight) {
                        switch($request->data){
                            case 1:
                                $query->whereIn('medical_actual_status', array(1, 4));
                                break;
                            case 3:
                                $query->whereIn('medical_actual_status', array(1, 4))
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->whereNotIn('medical_actual_status', array(1, 4));
                                break;
                            }
                        })
                    ->get()
                    ->pluck('medical_actual_status');

                break;
            case 2:
                // return $mobilizeId;
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('mofa_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('medical_online_completed', 1);
                                break;
                            case 3:
                                $query->where('medical_online_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('medical_online_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('mofa_completed', 1)
                    ->where('medical_online_completed', 0)

                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('mofa_completed', 1)
                    ->where('medical_online_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('mofa_completed', 1)
                    ->where('medical_online_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('mofa_completed', 1)
                    ->where('medical_online_completed', 1)
                    ->get()
                    ->pluck('medical_online_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_online_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where('ew_mobilization_master_tables.mofa_completed', 1)
                                    ->where('ew_mobilization_master_tables.medical_online_completed', 1)
                                    ->where(function($query) use ($search_val)
                                    {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    })
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('medical_online_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                ->where('mofa_completed', 1)
                                ->where('medical_online_completed', 1)
                                ->where('medical_online_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                ->get()
                                ->pluck('medical_online_date');
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date', 
                                'ew_candidatescv.passport_status', 'ew_candidatescv.wip_status','ew_mobilization_master_tables.medical_online_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.medical_online_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);


                        if ($this->projectCountryId == 185 || $this->projectCountryId == 172) {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                 ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                 ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                 ->where('ew_mobilization_master_tables.'.$dependencyObject[2],1)
                                 ->where(function($query) use ($search_val)
                                    {
                                    $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_mobilization_master_tables.medical_online_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                    })
                                    ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                    ->get()
                                    ->pluck($dependencyObject[1]);

                            $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                        }
                    }
                }

                break;
            case 3:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('medical_self_completed', 1);
                            break;
                        case 3:
                            $query->where('medical_self_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('medical_self_completed', 0);
                            break;
                        }
                    })
                ->get()
                ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('medical_self_completed', 0)

                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('medical_self_completed', 1)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('medical_self_completed', 1)
                ->whereNotIn('ew_candidatescv_id', $total_flight)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();

                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('medical_self_completed', 1);
                                break;
                            case 3:
                                $query->where('medical_self_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('medical_self_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('medical_self_date');

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_self_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('ew_mobilization_master_tables.medical_self_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.medical_self_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.medical_self_completed', 0);
                                                break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('medical_self_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_self_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('medical_self_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('medical_self_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('medical_self_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('medical_self_date');
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.medical_self_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.medical_self_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }
                break;
            case 4:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('fit_card_received_completed', 1);
                            break;
                        case 3:
                            $query->where('fit_card_received_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('fit_card_received_completed', 0);
                            break;
                        }
                    })
                ->get()
                ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('fit_card_received_completed', 0)

                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('fit_card_received_completed', 1)

                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('fit_card_received_completed', 1)
                ->whereNotIn('ew_candidatescv_id', $total_flight)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();


               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('fit_card_received_completed', 1);
                            break;
                        case 3:
                            $query->where('fit_card_received_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('fit_card_received_completed', 0);
                            break;
                        }
                    })
                ->get()
                ->pluck('fit_card_received_date');
                 
                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.fit_card_received_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('ew_mobilization_master_tables.fit_card_received_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.fit_card_received_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.fit_card_received_completed', 0);
                                                break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('fit_card_received_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('fit_card_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('fit_card_received_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('fit_card_received_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('fit_card_received_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('fit_card_received_date');
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.fit_card_received_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.fit_card_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }


                break;
            case 5:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('mofa_completed', 1);
                            break;
                        case 3:
                            $query->where('mofa_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('mofa_completed', 0);
                            break;
                        }
                    })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where('mofa_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where('mofa_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where('mofa_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('mofa_completed', 1);
                            break;
                        case 3:
                            $query->where('mofa_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('mofa_completed', 0);
                            break;
                        }
                    })
                    ->get()
                    ->pluck('mofa_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.mofa_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('mofa_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('mofa_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('mofa_completed', 0);
                                                break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('mofa_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->whereIn('medical_actual_status', array(1, 4))
                                    ->where('mofa_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                    switch($request->data){
                                        case 1:
                                            $query->where('mofa_completed', 1);
                                            break;
                                        case 3:
                                            $query->where('mofa_completed', 1)
                                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->where('mofa_completed', 0);
                                            break;
                                        }
                                    })
                                    ->get()
                                    ->pluck('mofa_date');
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.mofa_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.mofa_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 172) {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                    ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                    ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                    ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.mofa_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                    ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                    ->get()
                                    ->pluck($dependencyObject[1]);
                        }
                    }
                }
                break;
            case 6:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_document_sent_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_document_sent_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_document_sent_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_document_sent_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_document_sent_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_document_sent_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_document_sent_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_document_sent_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_document_sent_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('visa_document_sent_date');

                $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_document_sent_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.embassy_submission_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('ew_mobilization_master_tables.visa_document_sent_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.visa_document_sent_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                            $query->where('ew_mobilization_master_tables.visa_document_sent_completed', 0);
                                            break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('visa_document_sent_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('embassy_submission_completed', 1)
                                    ->where('visa_document_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('visa_document_sent_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('visa_document_sent_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('visa_document_sent_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('visa_document_sent_date');
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.visa_document_sent_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.visa_document_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                        $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.visa_document_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                    }
                }

                break;
            case 7:
               $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('embassy_submission_completed', 1);
                                    break;
                                case 3:
                                    $query->where('embassy_submission_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('embassy_submission_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                        
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if ($this->projectCountryId == 46) {

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where('embassy_submission_completed', 0)

                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where('embassy_submission_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->whereIn('medical_actual_status', array(1, 4))
                    ->where('embassy_submission_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->whereIn('medical_actual_status', array(1, 4))
                            ->where(function($query) use($request, $total_flight){
                                switch($request->data){
                                    case 1:
                                        $query->where('embassy_submission_completed', 1);
                                        break;
                                    case 3:
                                        $query->where('embassy_submission_completed', 1)
                                        ->whereNotIn('ew_candidatescv_id', $total_flight);
                                        break;
                                    default:
                                        $query->where('embassy_submission_completed', 0);
                                        break;
                                    }
                                })
                            ->get()
                            ->pluck('embassy_submission_date'); 

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE) 
                            {
                               $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.embassy_submission_date')
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->whereIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4))
                                    ->where('ew_candidatescv.valid',1)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('ew_mobilization_master_tables.embassy_submission_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.embassy_submission_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                            $query->where('ew_mobilization_master_tables.embassy_submission_completed', 0);
                                            break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('embassy_submission_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->whereIn('medical_actual_status', array(1, 4))
                                    ->where('embassy_submission_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('embassy_submission_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('embassy_submission_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('embassy_submission_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('embassy_submission_date'); 
                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.embassy_submission_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                } 
                else
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_online_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('embassy_submission_completed', 1);
                                    break;
                                case 3:
                                    $query->where('embassy_submission_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('embassy_submission_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_online_completed', 1)
                    ->where('embassy_submission_completed', 0)

                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_online_completed', 1)
                    ->where('embassy_submission_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_online_completed', 1)
                    ->where('embassy_submission_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

        
                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_online_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('embassy_submission_completed', 1);
                                    break;
                                case 3:
                                    $query->where('embassy_submission_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('embassy_submission_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('embassy_submission_date');

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE) 
                            {
                               $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.embassy_submission_date')
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where('ew_mobilization_master_tables.medical_online_completed', 1)
                                    ->where('ew_candidatescv.valid',1)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('embassy_submission_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('embassy_submission_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                            $query->where('embassy_submission_completed', 0);
                                            break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('embassy_submission_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_online_completed', 1)
                                    ->where('embassy_submission_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('embassy_submission_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('embassy_submission_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('embassy_submission_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('embassy_submission_date');

                            }

                            if ($this->projectCountryId == 185 || $this->projectCountryId == 172) {
                                $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.embassy_submission_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                            }
                        }
                    }
                }

                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                        ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                        ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                        ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                            'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status',
                            'ew_candidatescv.approved_date', 'ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                            'ew_mobilization_master_tables.embassy_submission_date')
                        ->where('ew_candidatescv.valid',1)
                        ->where(function($query) use ($search_val)
                        {
                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                ->orWhere('ew_mobilization_master_tables.embassy_submission_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                        })
                        ->where('ew_candidatescv.result', 1)
                        ->whereIn('ew_candidatescv.id', $candidateIds)
                        ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                        ->paginate(100);

                break;
            case 8:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('medical_online_completed', 1)
                ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('visa_completed', 1);
                            break;
                        case 3:
                            $query->where('visa_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('visa_completed', 0);
                            break;
                        }
                    })
                ->get()
                ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('visa_completed', 0)

                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();
                
                
                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->where('visa_completed', 1)->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('visa_completed', 1)
                ->whereNotIn('ew_candidatescv_id', $total_flight)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_online_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('visa_completed', 1);
                                break;
                            case 3:
                                $query->where('visa_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('visa_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('visa_date');

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.medical_online_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                            $query->where('ew_mobilization_master_tables.visa_completed', 1);
                                            break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.visa_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                            $query->where('ew_mobilization_master_tables.visa_completed', 0);
                                            break;
                                        }

                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('visa_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_online_completed', 1)
                                    ->where('visa_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('visa_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('visa_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('visa_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('visa_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.visa_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.visa_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }
                break;
            case 9:
                // return $mobilizeId;
                if ($this->projectCountryId == 180) 
                {
                    $visa_completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->get()    
                    ->pluck('ew_candidatescv_id');

                    $candidateIds = EwCandidatesCV::where('ew_project_id', $projectId)
                        ->where('approved_status', 1)
                        ->whereNotIn('id', $visa_completed)
                        ->get()
                        ->pluck('id');
                        
                    if ($request->data == 1) {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->get()
                    ->pluck('ew_candidatescv_id');
                    }

                    if ($request->data == 2) {
                    $candidateIds = EwCandidatesCV::where('ew_project_id', $projectId)
                        ->where('approved_status', 1)
                        ->whereNotIn('id', $visa_completed)
                        ->get()
                        ->pluck('id');
                    }
                    
                    if ($request->data == 3) {
                        $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_online_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('visa_online_completed', 1);
                                break;
                            case 3:
                                $query->where('visa_online_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            }
                        })
                        ->get()
                        ->pluck('ew_candidatescv_id');
                    }

                    $completed = $total_visa_completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();


                    $incompleted = EwCandidatesCV::where('ew_project_id', $projectId)
                        ->where('approved_status', 1)
                        ->count() - $total_visa_completed;

                   $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_online_completed', 1)
                            ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_online_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_online_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('visa_online_date');

                    $prevDate = EwCandidatesCV::where('ew_project_id', $this->projectId)
                        ->whereIn('id', $candidateIds)
                        ->get()
                        ->pluck( $dependencyObject[1]);
                        $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_online_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.visa_online_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.visa_online_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.visa_online_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('visa_online_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('visa_online_completed', 1)
                                        ->where('visa_online_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('visa_online_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('visa_online_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('visa_online_date');

                            }

                            $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                                ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                                ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                                ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                                ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                    'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                    'ew_mobilization_master_tables.visa_online_date')
                                ->where('ew_candidatescv.valid',1)
                                ->where(function($query) use ($search_val)
                                {
                                    $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_mobilization_master_tables.visa_online_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                })
                                ->where('ew_candidatescv.result', 1)
                                ->whereIn('ew_candidatescv.id', $candidateIds)
                                ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                                ->paginate(100);

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                        'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                         ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                         ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                         ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                         ->where(function($query) use ($search_val)
                                            {
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_mobilization_master_tables.visa_online_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                            })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);

                        }
                    }

                
                }
                break;
            case 10:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_document_sent_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_print_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_print_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_print_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');
                        
                if ($this->projectCountryId == 180 || $this->projectCountryId == 46) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                } 

                if ($this->projectCountryId == 46) 
                {

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_document_sent_completed', 1)
                        ->where('visa_print_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_document_sent_completed', 1)
                        ->where('visa_print_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_document_sent_completed', 1)
                        ->where('visa_print_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('visa_document_sent_completed', 1)
                            ->where(function($query) use($request, $total_flight){
                                switch($request->data){
                                    case 1:
                                        $query->where('visa_print_completed', 1);
                                        break;
                                    case 3:
                                        $query->where('visa_print_completed', 1)
                                        ->whereNotIn('ew_candidatescv_id', $total_flight);
                                        break;
                                    default:
                                        $query->where('visa_print_completed', 0);
                                        break;
                                    }
                                })
                            ->get()
                            ->pluck('visa_print_date');

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_print_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.visa_document_sent_completed', 1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                           switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.visa_print_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.visa_print_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.visa_print_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('visa_print_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->whereIn('medical_actual_status', array(1, 4))
                                        ->where('visa_print_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('visa_print_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('visa_print_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('visa_print_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('visa_print_date');

                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.visa_print_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                } 
                else 
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_print_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_print_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_print_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where('visa_print_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where('visa_print_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where('visa_print_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_print_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_print_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_print_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('visa_print_date');

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_print_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->whereIn('ew_mobilization_master_tables.medical_actual_status', array(1, 4))
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                           switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.visa_print_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.visa_print_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.visa_print_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('visa_print_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->whereIn('medical_actual_status', array(1, 4))
                                        ->where('visa_print_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('visa_print_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('visa_print_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('visa_print_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('visa_print_date');

                            }

                            if ($this->projectCountryId == 180)
                            {
                                $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.visa_print_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                    ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                    ->get()
                                    ->pluck($dependencyObject[1]);
                            }
                            
                        }
                    }
 
                }

                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.visa_print_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.visa_print_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                            
                
                break;
            case 11:
                if ($this->projectCountryId == 46) 
                {

                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_attached_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_attached_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_attached_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where('visa_attached_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where('visa_attached_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where('visa_attached_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_attached_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_attached_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_attached_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('visa_attached_date');

                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                    
                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_attached_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.visa_print_completed', 1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                           switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.visa_attached_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.visa_attached_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.visa_attached_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('visa_attached_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('visa_print_completed', 1)
                                         ->where('visa_attached_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('visa_attached_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('visa_attached_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('visa_attached_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('visa_attached_date');
                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.visa_attached_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                    ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                    ->get()
                                    ->pluck($dependencyObject[1]);
                        }
                    }

                } 
                else 
                {

                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_attached_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_attached_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_attached_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_attached_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_attached_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_attached_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('visa_attached_completed', 1);
                                    break;
                                case 3:
                                    $query->where('visa_attached_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('visa_attached_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('visa_attached_date');

                    if ($this->projectCountryId == 185 || $this->projectCountryId == 172)
                    {
                        $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->whereIn('ew_candidatescv_id', $candidateIds)
                            ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                            ->where($dependencyObject[2], 1)
                            ->get()
                            ->pluck($dependencyObject[1]);

                        $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                    }
            
                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.visa_attached_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.embassy_submission_completed', 1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                           switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.visa_attached_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.visa_attached_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.visa_attached_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('visa_attached_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('embassy_submission_completed', 1)
                                        ->where('visa_attached_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('visa_attached_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('visa_attached_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('visa_attached_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('visa_attached_date');

                            }
                            
                            if ($this->projectCountryId == 185 || $this->projectCountryId == 172)
                            {
                                $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.visa_attached_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                            }

                        }
                    }
                }
               
                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.visa_attached_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.visa_attached_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                 
                break;
            case 12:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('pcc_completed', 1);
                                break;
                            case 3:
                                $query->where('pcc_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('pcc_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('pcc_completed', 0)

                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('pcc_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('pcc_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();  

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('pcc_completed', 1);
                                break;
                            case 3:
                                $query->where('pcc_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('pcc_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('pcc_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                } 

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.pcc_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.medical_sent_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                        case 1:
                                            $query->where('ew_mobilization_master_tables.pcc_completed', 1);
                                            break;
                                        case 3:
                                            $query->where('ew_mobilization_master_tables.pcc_completed', 1)
                                            ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->where('ew_mobilization_master_tables.pcc_completed', 0);
                                            break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('pcc_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_sent_completed', 1)
                                    ->where('pcc_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                         switch($request->data){
                                            case 1:
                                                $query->where('pcc_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('pcc_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('pcc_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('pcc_date');

                        }

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.pcc_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        } 

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.pcc_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.pcc_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }

                
                break;
            case 13:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('gttc_completed', 1);
                            break;
                        case 3:
                            $query->where('gttc_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('gttc_completed', 0);
                            break;
                        }
                    })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('gttc_completed', 0)
    
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('gttc_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('gttc_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();


               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('gttc_completed', 1);
                            break;
                        case 3:
                            $query->where('gttc_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('gttc_completed', 0);
                            break;
                        }
                    })
                    ->get()
                    ->pluck('gttc_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 46 || 
                    $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.gttc_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.medical_sent_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.gttc_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.gttc_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.gttc_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('gttc_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_sent_completed', 1)
                                    ->where('gttc_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                    switch($request->data){
                                        case 1:
                                            $query->where('gttc_completed', 1);
                                            break;
                                        case 3:
                                            $query->where('gttc_completed', 1)
                                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->where('gttc_completed', 0);
                                            break;
                                        }
                                    })
                                    ->get()
                                    ->pluck('gttc_date');

                        }

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 46 || 
                            $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.gttc_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.gttc_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.gttc_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }

                break;
            case 14:
                
                if ($this->projectCountryId == 180) 
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('fingerprint_completed', 1);
                                    break;
                                case 3:
                                    $query->where('fingerprint_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('fingerprint_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');
                    
                     $completed  = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where('fingerprint_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where('fingerprint_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where('fingerprint_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                   $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('fingerprint_completed', 1);
                                    break;
                                case 3:
                                    $query->where('fingerprint_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('fingerprint_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('fingerprint_date');

                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.fingerprint_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.visa_print_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                               case 1:
                                                    $query->where('ew_mobilization_master_tables.fingerprint_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.fingerprint_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.fingerprint_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('fingerprint_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('visa_print_completed', 1)
                                        ->where('fingerprint_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('fingerprint_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('fingerprint_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('fingerprint_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('fingerprint_date');

                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.fingerprint_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);

                        }
                    }

                    
                }

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_attached_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('fingerprint_completed', 1);
                                    break;
                                case 3:
                                    $query->where('fingerprint_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('fingerprint_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');
                    
                     $completed  = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_attached_completed', 1)
                        ->where('fingerprint_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_attached_completed', 1)
                        ->where('fingerprint_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_attached_completed', 1)

                        ->where('fingerprint_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                   $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_attached_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('fingerprint_completed', 1);
                                    break;
                                case 3:
                                    $query->where('fingerprint_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('fingerprint_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('fingerprint_date');

                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.fingerprint_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.visa_attached_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.fingerprint_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.fingerprint_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.fingerprint_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('fingerprint_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('visa_attached_completed', 1)
                                        ->where('fingerprint_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('fingerprint_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('fingerprint_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('fingerprint_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('fingerprint_date');

                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.fingerprint_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }

                }  

                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.fingerprint_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.fingerprint_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                break;
            case 15:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('fingerprint_completed', 1)
                ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('bmet_completed', 1);
                            break;
                        case 3:
                            $query->where('bmet_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('bmet_completed', 0);
                            break;
                        }
                    })
                ->get()
                ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('fingerprint_completed', 1)
                    ->where('bmet_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('fingerprint_completed', 1)
                ->where('bmet_completed', 1)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)    
                ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                 ->where('fingerprint_completed', 1)
                ->where('bmet_completed', 1)
                ->whereNotIn('ew_candidatescv_id', $total_flight)
                ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                ->count();


               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('fingerprint_completed', 1)
                ->where(function($query) use($request, $total_flight){
                    switch($request->data){
                        case 1:
                            $query->where('bmet_completed', 1);
                            break;
                        case 3:
                            $query->where('bmet_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                            break;
                        default:
                            $query->where('bmet_completed', 0);
                            break;
                        }
                    })
                    ->get()
                    ->pluck('bmet_date');

                if ($this->projectCountryId == 180) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }    

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.bmet_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.fingerprint_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.bmet_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.bmet_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.bmet_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('bmet_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                ->where('fingerprint_completed', 1)
                                ->where('bmet_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                ->where(function($query) use($request, $total_flight){
                                    switch($request->data){
                                        case 1:
                                            $query->where('bmet_completed', 1);
                                            break;
                                        case 3:
                                            $query->where('bmet_completed', 1)
                                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->where('bmet_completed', 0);
                                            break;
                                        }
                                    })
                                    ->get()
                                    ->pluck('bmet_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.bmet_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.bmet_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                        if ($this->projectCountryId == 180) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.bmet_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }    

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.bmet_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }

                    }
                }

                
                break;
            case 16:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('gamca_completed', 1);
                                break;
                            case 3:
                                $query->where('gamca_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('gamca_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gamca_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gamca_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gamca_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();


               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('gamca_completed', 1);
                                break;
                            case 3:
                                $query->where('gamca_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('gamca_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('gamca_date');

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.gamca_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.gamca_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.gamca_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.gamca_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('gamca_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                ->where('gamca_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                ->where(function($query) use($request, $total_flight){
                                    switch($request->data){
                                        case 1:
                                            $query->where('gamca_completed', 1);
                                            break;
                                        case 3:
                                            $query->where('gamca_completed', 1)
                                            ->whereNotIn('ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->where('gamca_completed', 0);
                                            break;
                                        }
                                    })
                                ->get()
                                ->pluck('gamca_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.gamca_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.gamca_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }
                break;
            case 17:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('medical_slip_completed', 1);
                                break;
                            case 3:
                                $query->where('medical_slip_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('medical_slip_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('medical_slip_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where('medical_slip_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                     ->where('medical_sent_completed', 1)
                    ->where('medical_slip_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_sent_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('medical_slip_completed', 1);
                                break;
                            case 3:
                                $query->where('medical_slip_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('medical_slip_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('medical_slip_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }


                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_slip_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.medical_sent_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.medical_slip_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.medical_slip_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.medical_slip_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('medical_slip_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_sent_completed', 1)
                                    ->where('medical_slip_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('medical_slip_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('medical_slip_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('medical_slip_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('medical_slip_date');
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.medical_slip_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.medical_slip_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);


                        if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.medical_slip_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                }

                
                break;
            case 18:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('document_sent_completed', 1);
                                break;
                            case 3:
                                $query->where('document_sent_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('document_sent_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('document_sent_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('document_sent_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                 $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                     ->where('document_sent_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                     ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('document_sent_completed', 1);
                                break;
                            case 3:
                                $query->where('document_sent_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('document_sent_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('document_sent_date');

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.document_sent_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.document_sent_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.document_sent_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.document_sent_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('document_sent_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('document_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                     ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('document_sent_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('document_sent_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('document_sent_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('document_sent_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.document_sent_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.document_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }


                break;
            case 19:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_actual_status', 2)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('remedical_completed', 1);
                                break;
                            case 3:
                                $query->where('remedical_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('remedical_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_actual_status', 2)
                    ->where('remedical_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_actual_status', 2)
                    ->where('remedical_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_actual_status', 2)
                    ->where('remedical_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_actual_status', 2)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('remedical_completed', 1);
                                break;
                            case 3:
                                $query->where('remedical_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('remedical_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('remedical_date');
                
                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.remedical_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.medical_actual_status',2)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.remedical_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.remedical_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.remedical_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('remedical_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_actual_status', 2)
                                    ->where('remedical_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('remedical_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('remedical_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('remedical_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('remedical_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.remedical_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.remedical_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                }

                break;
            case 20:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pcc_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('pcc_received_completed', 1);
                                break;
                            case 3:
                                $query->where('pcc_received_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('pcc_received_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pcc_completed', 1)
                    ->where('pcc_received_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pcc_completed', 1)
                    ->where('pcc_received_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pcc_completed', 1)
                    ->where('pcc_received_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();


               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pcc_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('pcc_received_completed', 1);
                                break;
                            case 3:
                                $query->where('pcc_received_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('pcc_received_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('pcc_received_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

               
                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.pcc_received_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.pcc_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.pcc_received_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.pcc_received_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.pcc_received_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('pcc_received_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('pcc_completed', 1)
                                    ->where('pcc_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('pcc_received_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('pcc_received_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('pcc_received_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('pcc_received_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.pcc_received_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.pcc_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.pcc_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                }
                break;
            case 21:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gttc_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('gttc_received_completed', 1);
                                break;
                            case 3:
                                $query->where('gttc_received_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('gttc_received_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');
                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gttc_completed', 1)
    
                    ->where('gttc_received_completed', 0)
    
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gttc_completed', 1)
                    ->where('gttc_received_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gttc_completed', 1)
                    ->where('gttc_received_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();
    

                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('gttc_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('gttc_received_completed', 1);
                                break;
                            case 3:
                                $query->where('gttc_received_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('gttc_received_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('gttc_received_date');

                
                if ($this->projectCountryId == 180 || $this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.gttc_received_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.gttc_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.gttc_received_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.gttc_received_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.gttc_received_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('gttc_received_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('gttc_completed', 1)
                                    ->where('gttc_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('gttc_received_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('gttc_received_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('gttc_received_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('gttc_received_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.gttc_received_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.gttc_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);


                        if ($this->projectCountryId == 180 || $this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.gttc_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }

                    }
                }
                break;
            case 22:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)        ->where('bmet_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('smartcard_completed', 1);
                                break;
                            case 3:
                                $query->where('smartcard_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('smartcard_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('bmet_completed', 1)
                    ->where('smartcard_completed', 0)
    
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('bmet_completed', 1)
                    ->where('smartcard_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('bmet_completed', 1)
                    ->where('smartcard_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();
    

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('bmet_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('smartcard_completed', 1);
                                break;
                            case 3:
                                $query->where('smartcard_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('smartcard_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('smartcard_date'); 

                if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 46 || $this->projectCountryId == 172) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }


                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.smartcard_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.bmet_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.smartcard_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.smartcard_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.smartcard_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('smartcard_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('bmet_completed', 1)
                                    ->where('smartcard_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('smartcard_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('smartcard_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('smartcard_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('smartcard_date'); 

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.smartcard_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.smartcard_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);


                        $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.smartcard_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                    }
                } 
                break;
            case 23:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('smartcard_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('pta_request_completed', 1);
                                break;
                            case 3:
                                $query->where('pta_request_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('pta_request_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('smartcard_completed', 1)
                    ->where('pta_request_completed', 0)
    
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('smartcard_completed', 1)
                    ->where('pta_request_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('smartcard_completed', 1)
                    ->where('pta_request_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('smartcard_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('pta_request_completed', 1);
                                break;
                            case 3:
                                $query->where('pta_request_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('pta_request_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('pta_request_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);
                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.pta_request_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.smartcard_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.pta_request_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.pta_request_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.pta_request_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('pta_request_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('smartcard_completed', 1)
                                    ->where('pta_request_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('pta_request_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('pta_request_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('pta_request_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('pta_request_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.pta_request_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.pta_request_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                         {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.pta_request_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                } 

                break;
            case 24:
                if ($this->projectCountryId == 180) 
                {

                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('smartcard_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('pta_received_completed', 1);
                                    break;
                                case 3:
                                    $query->where('pta_received_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('pta_received_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('smartcard_completed', 1)
                        ->where('pta_received_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('smartcard_completed', 1)
                        ->where('pta_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('smartcard_completed', 1)
                        ->where('pta_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();
       

                   $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('smartcard_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('pta_received_completed', 1);
                                    break;
                                case 3:
                                    $query->where('pta_received_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('pta_received_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('pta_received_date');

                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.pta_received_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.smartcard_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.pta_received_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.pta_received_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.pta_received_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('pta_received_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('smartcard_completed', 1)
                                        ->where('pta_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('pta_received_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('pta_received_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('pta_received_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('pta_received_date');
                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.pta_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    } 

                } // end 180

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId == 172) 
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_request_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('pta_received_completed', 1);
                                    break;
                                case 3:
                                    $query->where('pta_received_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('pta_received_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_request_completed', 1)
                        ->where('pta_received_completed', 0)
        
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_request_completed', 1)
                        ->where('pta_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_request_completed', 1)
                        ->where('pta_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                   $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_request_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('pta_received_completed', 1);
                                    break;
                                case 3:
                                    $query->where('pta_received_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('pta_received_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('pta_received_date');

                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                    

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.pta_received_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.pta_request_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.pta_received_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.pta_received_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.pta_received_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('pta_received_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('pta_request_completed', 1)
                                        ->where('pta_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('pta_received_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('pta_received_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('pta_received_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('pta_received_date');
                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.pta_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    } 
                } // end 185 or 46

                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.pta_received_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.pta_received_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                break;
            case 25:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('flight_briefing_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('flight_completed', 1);
                                break;
                            case 3:
                                $query->where('flight_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('flight_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('flight_briefing_completed', 1)
                    ->where('flight_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                   ->where('flight_briefing_completed', 1)
                    ->where('flight_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('flight_briefing_completed', 1)
                    ->where('flight_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();
    

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('flight_briefing_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('flight_completed', 1);
                                break;
                            case 3:
                                $query->where('flight_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('flight_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('flight_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 172) 
                {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                        $prevDate = $prevDate;
                        
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.flight_date','ew_mobilization_master_tables.flight_briefing_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.flight_briefing_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.flight_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.flight_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.flight_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('flight_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('flight_briefing_completed', 1)
                                    ->where('flight_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('flight_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('flight_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('flight_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('flight_date');

                        }


                        if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 172) 
                        {
                                $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                    ->where('ew_mobilization_master_tables.'.$dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                                    ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                    ->where(function($query) use ($search_val)
                                    {
                                    $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                        ->orWhere('ew_mobilization_master_tables.flight_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                    })
                                    ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                    ->get()
                                    ->pluck($dependencyObject[1]);
                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.flight_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.flight_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);
                    }
                } 
                break;
            case 26:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pta_received_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('flight_briefing_completed', 1);
                                break;
                            case 3:
                                $query->where('flight_briefing_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('flight_briefing_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                   ->where('pta_received_completed', 1)
                    ->where('flight_briefing_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pta_received_completed', 1)
                    ->where('flight_briefing_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                 $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pta_received_completed', 1)
                    ->where('flight_briefing_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();
   

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pta_received_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('flight_briefing_completed', 1);
                                break;
                            case 3:
                                $query->where('flight_briefing_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('flight_briefing_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('flight_briefing_date');

                if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 172) 
                {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                $data['flight_date'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('pta_received_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('flight_briefing_completed', 1);
                                break;
                            case 3:
                                $query->where('flight_briefing_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('flight_briefing_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('flight_date');

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.flight_briefing_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.pta_received_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.flight_briefing_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.flight_briefing_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.flight_briefing_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('flight_briefing_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('pta_received_completed', 1)
                                    ->where('flight_briefing_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('flight_briefing_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('flight_briefing_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('flight_briefing_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('flight_briefing_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.flight_briefing_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.flight_briefing_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);

                        if ($this->projectCountryId == 185 || $this->projectCountryId == 180 || $this->projectCountryId == 172) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.flight_briefing_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                                
                        }

                        $data['flight_date'] = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                     ->where('ew_mobilization_master_tables.pta_received_completed', 1)
                                     ->where(function($query) use($request, $total_flight){
                                    switch($request->data){
                                        case 1:
                                            $query->where('ew_mobilization_master_tables.flight_briefing_completed', 1);
                                            break;
                                        case 3:
                                            $query->where('ew_mobilization_master_tables.flight_briefing_completed', 1)
                                            ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                            break;
                                        default:
                                            $query->where('ew_mobilization_master_tables.flight_briefing_completed', 0);
                                            break;
                                        }
                                    })
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.flight_briefing_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                    ->get()
                                    ->pluck('flight_date');

                    }
                } 
                break;
            case 27:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('qvc_appointment_completed', 1);
                                break;
                            case 3:
                                $query->where('qvc_appointment_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('qvc_appointment_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->where('qvc_appointment_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->where('qvc_appointment_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->where('qvc_appointment_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();
    

               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_online_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('qvc_appointment_completed', 1);
                                break;
                            case 3:
                                $query->where('qvc_appointment_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('qvc_appointment_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('qvc_appointment_date');

                if ($this->projectCountryId == 180) {
                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;
                }

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.qvc_appointment_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.visa_online_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.qvc_appointment_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.qvc_appointment_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.qvc_appointment_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('qvc_appointment_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('visa_online_completed', 1)
                                    ->where('qvc_appointment_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('qvc_appointment_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('qvc_appointment_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('qvc_appointment_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('qvc_appointment_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.qvc_appointment_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.qvc_appointment_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);


                        if ($this->projectCountryId == 180) 
                        {
                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.qvc_appointment_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    }
                }       
                break;
            case 28:
                $candidateIds = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('approved_status', 1);
                                break;
                            case 3:
                                $query->where('approved_status', 1)
                                ->whereNotIn('id', $total_flight);
                                break;
                            default:
                                $query->where('approved_status', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('id');

                $completed = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                    ->where('approved_status', 1)
                    ->where('result', 1)
                    ->count();

                $wip = EwCandidatesCV::where('ew_project_id', $projectId)
                ->where('approved_status', 1)
                ->where('result', 1)
                ->whereNotIn('id', $total_flight)
                ->count();
    

                $incompleted = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                    ->where('approved_status', 0)
                    ->where('result', 1)
                    ->count();

               $mobilization_date = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('approved_status', 1);
                                break;
                            case 3:
                                $query->where('approved_status', 1)
                                ->whereNotIn('id', $total_flight);
                                break;
                            default:
                                $query->where('approved_status', 0);
                                break;
                            }
                        })
                    ->where('result', 1)
                    ->get()
                    ->pluck('approved_date');

                if ($this->projectCountryId == 180 || $this->projectCountryId == 185 || $this->projectCountryId == 172) {
                    $prevDate = EwInterview::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->get()
                        ->pluck('selection_date');

                    $data['depMobilizeId'] = false;
                } 

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                            switch($request->data){
                                                case 1:
                                                    $query->where('approved_status', 1);
                                                    break;
                                                case 3:
                                                    $query->where('approved_status', 1)
                                                    ->whereNotIn('id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('approved_status', 0);
                                                    break;
                                                }
                                                 $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        })
                                        ->where('result', 1)
                                        ->whereIn('id', $candidateIds)
                                        ->get()
                                        ->pluck('approved_date');

                        }
                        else
                        {
                            $mobilization_date = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                                    ->where('approved_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('approved_status', 1);
                                                    break;
                                                case 3:
                                                    $query->where('approved_status', 1)
                                                    ->whereNotIn('id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('approved_status', 0);
                                                    break;
                                                }
                                            })
                                        ->where('result', 1)
                                        ->get()
                                        ->pluck('approved_date');

                        }

                        $candidateDetail = EwCandidatesCV::leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.approved_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_candidatescv.ew_project_id',$projectId)
                            ->paginate(100);

                        if ($this->projectCountryId == 180 || $this->projectCountryId == 185 || $this->projectCountryId == 172) 
                        {

                            $prevDate = EwInterview::leftjoin('ew_candidatescv', 
                                    'ew_interviews.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_interviews.ew_project_id', $this->projectId)
                                     ->whereIn('ew_interviews.ew_candidatescv_id', $candidateIds)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.approved_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_interviews.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck('selection_date');
                                
                        } 
                    }
                } 

                $p_expire_date = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                                            ->where('result', 1)
                                            ->whereIn('id',$candidateIds)
                                            ->get()->pluck('passport_expired_date');
                     

                $data['passport_expire_countdown'] = $p_expire_date;
                break;
            case 29:
                if ($this->projectCountryId == 180) 
                {
                    $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('qvc_appointment_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('medical_call_completed', 1);
                                    break;
                                case 3:
                                    $query->where('medical_call_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('medical_call_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('qvc_appointment_completed', 1)
                        ->where('medical_call_completed', 0)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('qvc_appointment_completed', 1)
                        ->where('medical_call_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('qvc_appointment_completed', 1)
                        ->where('medical_call_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('qvc_appointment_completed', 1)
                        ->where(function($query) use($request, $total_flight){
                            switch($request->data){
                                case 1:
                                    $query->where('medical_call_completed', 1);
                                    break;
                                case 3:
                                    $query->where('medical_call_completed', 1)
                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                    break;
                                default:
                                    $query->where('medical_call_completed', 0);
                                    break;
                                }
                            })
                        ->get()
                        ->pluck('medical_call_date');

                    $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->whereIn('ew_candidatescv_id', $candidateIds)
                        ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                        ->where($dependencyObject[2], 1)
                        ->get()
                        ->pluck($dependencyObject[1]);

                    $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;   


                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_call_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.qvc_appointment_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($request, $total_flight,$search_val) {
                                        switch($request->data){
                                                case 1:
                                                    $query->where('ew_mobilization_master_tables.medical_call_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('ew_mobilization_master_tables.medical_call_completed', 1)
                                                    ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('ew_mobilization_master_tables.medical_call_completed', 0);
                                                    break;
                                            }
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('medical_call_date');  
                            }
                            else
                            {
                                $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('qvc_appointment_completed', 1)
                                        ->where('medical_call_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->where(function($query) use($request, $total_flight){
                                            switch($request->data){
                                                case 1:
                                                    $query->where('medical_call_completed', 1);
                                                    break;
                                                case 3:
                                                    $query->where('medical_call_completed', 1)
                                                    ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                    break;
                                                default:
                                                    $query->where('medical_call_completed', 0);
                                                    break;
                                                }
                                            })
                                        ->get()
                                        ->pluck('medical_call_date');

                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                     ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.medical_call_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                        ->get()
                                        ->pluck($dependencyObject[1]);
                        }
                    } 

                }

                if ($this->projectCountryId == 185 || $this->projectCountryId == 46 || $this->projectCountryId = 172) 
                {
                    $total_medical_call = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_call_completed', 1)
                        ->get()
                        ->pluck('ew_candidatescv_id');

                    if ($request->data == 1) 
                    {
                        $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('medical_call_completed', 1)
                            ->get()
                            ->pluck('ew_candidatescv_id');
                    
                    }  
                    elseif ($request->data == 2) 
                    {
                        $candidateIds = Ewcandidatescv::valid()
                            ->where('ew_project_id', $projectId)
                            ->whereNotIn('id', $total_medical_call)
                            ->where('approved_status', 1)
                            ->get()
                            ->pluck('id');
                    } 
                    else if ($request->data == 3) 
                    {
                        $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                            ->where('medical_call_completed', 1)
                            ->whereNotIn('ew_candidatescv_id', $total_flight)
                            ->get()
                            ->pluck('ew_candidatescv_id');

                    }
                    else 
                    {
                        $candidateIds = Ewcandidatescv::valid()
                            ->where('ew_project_id', $projectId)
                            ->whereNotIn('id', $total_medical_call)
                            ->where('approved_status', 1)
                            ->get()
                            ->pluck('id');
                    }
                    $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_call_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();


                    $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_call_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $total_flight)
                        ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                        ->count();

                    $incompleted = EwCandidatescv::valid()
                        ->where('ew_project_id', $projectId)
                        ->where('approved_status', 1)
                        ->whereNotIn('id', $total_medical_call)
                        ->whereNotIn('id', $not_pass_candidates)
                        ->count();
        
                   $mobilization_date =  EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_call_completed', 1)
                        ->get()
                        ->pluck('medical_call_date');

                    $prevDate = EwCandidatesCV::where('ew_project_id', $this->projectId)
                        ->whereIn('id', $candidateIds)
                        ->get()
                        ->pluck('approved_date');

                    $data['depMobilizeId'] = 28;  

                    if(isset($search_val))
                    {
                        if($search_val == '')
                        {
                            $mobilization_date = $mobilization_date;
                        }
                        else
                        {   
                            if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                            {
                                $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                        ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                        'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_call_date')
                                        ->where('ew_candidatescv.valid',1)
                                        ->where('ew_mobilization_master_tables.medical_call_completed',1)
                                        ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                        ->where(function ($query) use ($search_val) {
                                            $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                                ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                        }) 
                                        ->where('ew_candidatescv.result', 1)
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('medical_call_date');  
                            }
                            else
                            {
                                $mobilization_date =  EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                        ->where('medical_call_completed', 1)
                                        ->where('medical_call_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                        ->get()
                                        ->pluck('medical_call_date');

                            }

                            $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                     ->where('ew_candidatescv.ew_project_id', $this->projectId)
                                     ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.medical_call_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                        ->whereIn('ew_candidatescv.id', $candidateIds)
                                        ->get()
                                        ->pluck('approved_date');

                        }
                    } 

                }   


                $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                    ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                    ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                    ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                        'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                        'ew_mobilization_master_tables.medical_call_date')
                    ->where('ew_candidatescv.valid',1)
                    ->where(function($query) use ($search_val)
                    {
                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                            ->orWhere('ew_mobilization_master_tables.medical_call_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                    })
                    ->where('ew_candidatescv.result', 1)
                    ->whereIn('ew_candidatescv.id', $candidateIds)
                    ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                    ->paginate(100); 
                
                break;
            case 30:
                $candidateIds = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_call_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('medical_sent_completed', 1);
                                break;
                            case 3:
                                $query->where('medical_sent_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('medical_sent_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('ew_candidatescv_id');

                $incompleted = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_call_completed', 1)
                    ->where('medical_sent_completed', 0)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
    
                    ->count();

                $completed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_call_completed', 1)
                    ->where('medical_sent_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();

                $wip = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_call_completed', 1)
                    ->where('medical_sent_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $total_flight)
                    ->whereNotIn('ew_candidatescv_id', $not_pass_candidates)
                    ->count();
    
               $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('medical_call_completed', 1)
                    ->where(function($query) use($request, $total_flight){
                        switch($request->data){
                            case 1:
                                $query->where('medical_sent_completed', 1);
                                break;
                            case 3:
                                $query->where('medical_sent_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                break;
                            default:
                                $query->where('medical_sent_completed', 0);
                                break;
                            }
                        })
                    ->get()
                    ->pluck('medical_sent_date');

                $prevDate = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->whereIn('ew_candidatescv_id', $candidateIds)
                    ->where($dependencyObject[0], $mobilizeDependency->mobilize_depended_on_id)
                    ->where($dependencyObject[2], 1)
                    ->get()
                    ->pluck($dependencyObject[1]);
                    
                $data['depMobilizeId'] = $mobilizeDependency->mobilize_depended_on_id;

                if(isset($search_val))
                {
                    if($search_val == '')
                    {
                        $mobilization_date = $mobilization_date;
                    }
                    else
                    {   
                        if (DateTime::createFromFormat('d-m-Y', $search_val) == FALSE)
                        {
                            $mobilization_date =  EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number',
                                    'ew_candidatescv.passport_no','ew_mobilization_master_tables.medical_sent_date')
                                    ->where('ew_candidatescv.valid',1)
                                    ->where('ew_mobilization_master_tables.medical_call_completed',1)
                                    ->where('ew_mobilization_master_tables.ew_project_id', $projectId)
                                    ->where(function ($query) use ($request, $total_flight,$search_val) {
                                    switch($request->data){
                                            case 1:
                                                $query->where('ew_mobilization_master_tables.medical_sent_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('ew_mobilization_master_tables.medical_sent_completed', 1)
                                                ->whereNotIn('ew_mobilization_master_tables.ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('ew_mobilization_master_tables.medical_sent_completed', 0);
                                                break;
                                        }
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%');
                                    }) 
                                    ->where('ew_candidatescv.result', 1)
                                    ->whereIn('ew_candidatescv.id', $candidateIds)
                                    ->get()
                                    ->pluck('medical_sent_date');  
                        }
                        else
                        {
                            $mobilization_date = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                    ->where('medical_call_completed', 1)
                                    ->where('medical_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%')
                                    ->where(function($query) use($request, $total_flight){
                                        switch($request->data){
                                            case 1:
                                                $query->where('medical_sent_completed', 1);
                                                break;
                                            case 3:
                                                $query->where('medical_sent_completed', 1)
                                                ->whereNotIn('ew_candidatescv_id', $total_flight);
                                                break;
                                            default:
                                                $query->where('medical_sent_completed', 0);
                                                break;
                                            }
                                        })
                                    ->get()
                                    ->pluck('medical_sent_date');

                        }

                        $candidateDetail = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 'ew_mobilization_master_tables.ew_candidatescv_id', '=', 'ew_candidatescv.id')
                            ->leftjoin('ew_references', 'ew_candidatescv.reference_id', '=', 'ew_references.id')
                            ->leftjoin('ew_trades','ew_candidatescv.selected_trade', '=', 'ew_trades.id')
                            ->leftjoin('ew_interviews','ew_candidatescv.id','=','ew_interviews.ew_candidatescv_id')
                            ->select('ew_candidatescv.id', 'ew_candidatescv.cv_number', 'ew_candidatescv.full_name', 'ew_candidatescv.passport_no', 'ew_candidatescv.selected_trade', 'ew_candidatescv.ew_project_id', 
                                'ew_candidatescv.reference_id', 'ew_candidatescv.contact_no', 'ew_candidatescv.approved_status','ew_candidatescv.approved_date','ew_candidatescv.passport_status', 'ew_candidatescv.wip_status',
                                'ew_mobilization_master_tables.medical_sent_date')
                            ->where('ew_candidatescv.valid',1)
                            ->where(function($query) use ($search_val)
                            {
                                $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_interviews.salary', 'LIKE', '%'.$search_val.'%')
                                    ->orWhere('ew_mobilization_master_tables.medical_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                            })
                            ->where('ew_candidatescv.result', 1)
                            ->whereIn('ew_candidatescv.id', $candidateIds)
                            ->where('ew_mobilization_master_tables.ew_project_id',$projectId)
                            ->paginate(100);


                        $prevDate = EwMobilizationMasterTable::leftjoin('ew_candidatescv', 
                                    'ew_mobilization_master_tables.ew_candidatescv_id', '=','ew_candidatescv.id')
                                    ->where('ew_mobilization_master_tables.ew_project_id', $this->projectId)
                                    ->where('ew_mobilization_master_tables.'.$dependencyObject[0],$mobilizeDependency->mobilize_depended_on_id)
                                    ->where('ew_mobilization_master_tables.'.$dependencyObject[2], 1)
                                    ->where(function($query) use ($search_val)
                                        {
                                        $query->where('ew_candidatescv.cv_number', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.full_name', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.passport_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_candidatescv.contact_no', 'LIKE', '%'.$search_val.'%')
                                            ->orWhere('ew_mobilization_master_tables.medical_sent_date', 'LIKE', '%'.date("Y-m-d", strtotime($search_val)).'%');
                                        })
                                    ->whereIn('ew_mobilization_master_tables.ew_candidatescv_id', $candidateIds)
                                    ->get()
                                    ->pluck($dependencyObject[1]);
                    }
                } 
                break;
            default:
                # code...
                break;
        }

        if(isset($search_val))
        {
            if($search_val == '')
            {
                $candidateDetail = EwCandidatesCV::valid()
                    ->whereIn('id', $candidateIds)
                    ->where('result', 1)
                    ->select('id', 'cv_number', 'full_name', 'passport_no', 'selected_trade', 'ew_project_id', 'reference_id', 'contact_no', 'approved_status','approved_date', 'passport_status', 'wip_status')
                    ->paginate(10);
            } else {
                $candidateDetail = $candidateDetail;
            }
            
        }
        else
        {
            $candidateDetail = EwCandidatesCV::valid()
                ->whereIn('id', $candidateIds)
                ->where('result', 1)
                ->select('id', 'cv_number', 'full_name', 'passport_no', 'selected_trade', 'ew_project_id', 'reference_id', 'contact_no', 'approved_status','approved_date', 'passport_status', 'wip_status')
                ->paginate(10);
        }

        if(isset($all_data))
        {
            $candidateDetail = EwCandidatesCV::valid()
                ->whereIn('id', $candidateIds)
                ->where('result', 1)
                ->select('id', 'cv_number', 'full_name', 'passport_no', 'selected_trade', 'ew_project_id', 'reference_id', 'contact_no', 'approved_status','approved_date', 'passport_status', 'wip_status')
                ->paginate(999);
        }


        $data['selectedTrades'] = EwTrades::valid()
        ->whereIn('id', $candidateDetail->pluck('selected_trade'))
        ->get()->count();
        

        if ($request->data != 0) {

            $filtering = $request->data;

        } else {

            $filtering = 2;

        }

        $data['filering']          = $filtering;
        $data['candidateDetails']  = $candidateDetail;
        $data['projectId']         = $this->projectId;
        $data['mobilizeId']        = $request->mobilizeId;
        $data['projectCountryId'] = $this->projectCountryId;

        $data['incompleted']       = !empty( $incompleted)? $incompleted: 0;
        $data['completed']         = !empty( $completed)? $completed  : 0;
        $data['wip']               = !empty( $wip)? $wip : 0;
        $data['mobilization_date'] = !empty($mobilization_date)? $mobilization_date : 0;
        $data[ 'prevDate'] =!empty($prevDate) ? $prevDate : 0;
        $data['interview_call_projects'] = EwInterviewCall::valid()->where('status', 1)->get();
        
        return view('recruitment.mobilization.mobilizationRoomCandidateData', $data);
    }

    public function getSelectedTrade(Request $request){
        $getTradeIds = EwCandidatescv::valid()
         ->where('ew_project_id', $this->projectId)
         ->groupBy('selected_trade')
         ->get()
         ->pluck('selected_trade');
        $data['projectId'] = $this->projectId;
        $data['selectedTrades'] = EwTrades::valid()->whereIn('id', $getTradeIds)->get();
        
        return view('recruitment.mobilization.selectedTradeList', $data);

    }


    public function singleCandidateMobilization(Request $request)
    {
        $data['projectId'] = $this->projectId;
        $data['candidateId'] = $this->candidateId;
        $data['inputData'] = $request->all();

        if (@Helper::checkAssignuser($this->projectId) == "notAllowed") {

            return "You have no access!";

        } else {

            return view('recruitment.mobilization.singleCandidateMobilization', $data);

        }
    }

    public function singleCandidateMobilizationData(Request $request)
    {
        $data['projectId'] = $projectId = $this->projectId;
        $data['candidateId'] = $candidateId = $this->candidateId;
        $data['mobilizeId'] = $mobilizeId = $request->mobilizeId;
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['project_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $mobilizationsList = EwConfiguration::valid()->where('ew_project_id', $projectId)->where(function ($query) use ($search) {
            $query->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('updated_at', 'LIKE', '%' . $search . '%');
        })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        $data['total_candidates'] = EwCandidatesCV::valid()->where('ew_project_id', $projectId)->count();

        $data['masterData'] = EwMobilizationMasterTable::valid()->where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->first();

        $data['mobilizeTemplate'] = self::mobilizeTemplate($projectId, $candidateId, $mobilizeId);

        $data['mobilizationsLists'] = $mobilizationsList;
        return view('recruitment.mobilization.singleCandidateMobilizationData', $data);
    }

    /*--------------------------------------------------------------------------
    MOBILIZE GENERAL PAGE VIEW
    --------------------------------------------------------------------------*/
    public function mobilizeTemplate($projectId, $candidateId, $mobilizeId)
    {
        $data['projectId'] = $projectId;
        $data['candidateId'] = $candidateId;
        $data['mobilizeId'] = $mobilizeId;
        switch ($mobilizeId) {
            case 1:
            case 2:
                $data['medical'] = Medical::valid()->where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->where('medical_id', $mobilizeId)->first();
                return view('recruitment.mobilization.medical', $data);
                break;
            case 8:
            case 9:
                $data['visa'] = Visa::valid()->where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->where('visa_online_id', $mobilizeId)->first();
                $data['masterTableData'] = EwMobilizationMasterTable::valid()->where('ew_project_id', $projectId)->select('visa_issued_date', 'visa_expiry_date')->where('ew_candidatescv_id', $candidateId)->first();

                return view('recruitment.mobilization.visa', $data);
                break;
            case 4:
            case 5:
            case 6:
            case 7:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                $data['masterTableData'] = EwMobilizationMasterTable::valid()->where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->first();
                return view('recruitment.mobilization.mobilizationGeneralPage', $data);
                break;

            default:
                # code...
                break;
        }

    }

    /*--------------------------------------------------------------------------
    Mobilize general page form
    --------------------------------------------------------------------------*/
    public function mobilizationGeneralForm(Request $request)
    {
        $data['projectId'] = $projectId = $this->projectId;
        $data['candidateId'] = $candidateId = $this->candidateId;
        $data['mobilizeId'] = $mobilizeId = $request->mobilizeId;
        return view('recruitment.mobilization.mobilizationGeneralForm', $data);
    }

    public function getFlightBriefingDate(Request $request)
    {

        $flight_brief_date = Carbon::parse($request->brief_date)->format('Y-m-d');
        $flight_date = EwMobilizationMasterTable::where('ew_project_id', $request->projectId)
                             ->where('ew_candidatescv_id', $request->candidate_id)
                             ->first()->flight_date;

        $flight_date = Carbon::parse($flight_date)->format('Y-m-d');

        if($flight_brief_date > $flight_date)
        {
            $message['msg'] = "Flight Date Should Be After Briefing Date";
            $message['status'] = "error";
            $message['flight_date'] = Carbon::parse($flight_date)->format('d-m-Y');
        }
        else
        {
           $message['status'] = "success"; 
        }
        
        return $message;
    }

    /*---------------------------------------------------------------------------------
    FOLLOW UP AND OPERATION DATA INSERTION
    -----------------------------------------------------------------------------------*/
    public function mobilizationGeneralFormDataStore(Request $request)
    {
        // return $this->projectCountryId;
        switch ($request->mobilizeId) {
            case 'candidates':

                break;
            case 1:
                // return $request->mobilizeId;
                $medicalData = [
                    'medical_id'            => $request->mobilizeId,
                    'medical_gone_date'     => Carbon::parse($request->medical_status_date)->format('Y-m-d'),
                    'medical_status_date'   => Carbon::parse($request->medical_status_date)->format('Y-m-d'),
                    // 'medical_name'       => $request->medical_name,
                    // 'medical_code'       => $request->medical_code,
                    'medical_actual_status' => $request->medical_actual_status,
                    'medical_completed'     => 1,
                    'medical_expire_date'   => (!empty($request->m_expire_date)? DateTime::createFromFormat('d-m-Y', $request->m_expire_date)->format('Y-m-d'):''),
                    'total_completed'       => $request->mobilizeId,
                ];

                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update($medicalData);

                $output['messege'] = 'Medical fit/unfit record saved';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 2:
                //return $request->mobilize_date;
                $mofa_date = EwMobilizationMasterTable::valid()
                    ->where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->first()->mofa_date;

                if(empty($mofa_date) || $mofa_date == "0000-00-00 00:00:00"){

                    $output['messege'] = 'At first update MOFA  date';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;

                }else{

                    $medical_online = Carbon::parse($request->medical_gone_date)->format('Y-m-d');
                    $medical_online_date = new DateTime($medical_online);
                    $mofa_date = new DateTime($mofa_date);

                    if($medical_online_date > $mofa_date){

                        $medicalOnlineData = [
                            'medical_online_id'        => $request->mobilizeId,
                            // 'medical_online_name'   => $request->medical_name,
                            // 'medical_online_code'   => $request->medical_code,
                            'medical_online_date'      => Carbon::parse($request->medical_gone_date)->format('Y-m-d'),
                            'medical_online_status'    => 1, // $request->medical_actual_status,
                            'medical_online_completed' => 1,
                            'total_completed'          => $request->mobilizeId,
                        ];
        
                        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->where('ew_candidatescv_id', $this->candidateId)
                            ->update($medicalOnlineData);
    
                        $output['messege'] = 'Medical Online record saved';
                        $output['msgType'] = 'success';
                        $output['status'] = 1;

                    }else{

                        $output['messege'] = 'Medical Online should be grather than MOFA date';
                        $output['msgType'] = 'danger';
                        $output['status'] = 0;
                    }

                }


                return $output;
                break;

            case 3:
                //return $request->mobilize_date;
                $medicalSelfData = [
                    'medical_self_id'        => $request->mobilizeId,
                    'medical_self_name'      => $request->medical_name,
                    'medical_self_code'      => $request->medical_code,
                    'medical_self_date'      => Carbon::parse($request->medical_gone_date)->format('Y-m-d'),
                    'medical_self_status'    => $request->medical_actual_status,
                    'medical_self_completed' => 1,
                    'total_completed'        => $request->mobilizeId,
                ];

                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update($medicalSelfData);
                $output['messege'] = 'Medical self has been completed';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 4:
                //return $request->mobilize_date;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'fit_card_id'                 => $request->mobilizeId,
                        'fit_card_received_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'fit_card_received_status'    => 1,
                        'fit_card_received_completed' => 1,
                        'total_completed'             => $request->mobilizeId,
                    ]);
                $output['messege'] = 'Fit card  basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 5:
                $medical_status_date = EwMobilizationMasterTable::valid()
                    ->where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->first()->medical_status_date;

                if(empty($medical_status_date) || $medical_status_date == "0000-00-00 00:00:00"){
                    $output['messege'] = 'At first update medicale status date';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;
                }else{
                    $mobilize_date = Carbon::parse($request->mobilize_date)->format('Y-m-d');
                    $mofaDate = new DateTime($mobilize_date);
                    $medical_status_date = new DateTime($medical_status_date);

                    if($mofaDate >= $medical_status_date){

                        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->where('ew_candidatescv_id', $this->candidateId)
                            ->update([
                                'mofa_id'         => $request->mobilizeId,
                                'mofa_date'       => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                                'mofa_status'     => 1,
                                'mofa_completed'  => 1,
                                'total_completed' => $request->mobilizeId,
                            ]);
                        $output['messege'] = 'Mofa basic info has been updated';
                        $output['msgType'] = 'success';
                        $output['status'] = 1;

                    }else{

                        $output['messege'] = 'Mofa date should be grather than medical status date';
                        $output['msgType'] = 'danger';
                        $output['status'] = 0;

                    }
                }
                
                return $output;
                break;
            case 6:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_document_sent_id'        => $request->mobilizeId,
                        'visa_document_sent_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'visa_document_sent_status'    => 1,
                        'visa_document_sent_completed' => 1,
                        'total_completed'              => $request->mobilizeId,
                    ]);
                $output['messege'] = 'Visa Document Sent basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 7: /*VISA EMBASSY SUBMISSION*/
                // return $request->mobilizeId;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'embassy_submission_id'        => $request->mobilizeId,
                        'embassy_submission_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'embassy_submission_status'    => 1,
                        'embassy_submission_completed' => 1,
                        'total_completed'              => $request->mobilizeId,
                    ]);
                $output['messege'] = 'Visa Submission  basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 8:
                $visaData = [
                    'visa_id'            => $request->mobilizeId,
                    'visa_issued_date'   => Carbon::parse($request->visa_issued_date)->format('Y-m-d'),
                    'visa_expiry_date'   => Carbon::parse($request->visa_expiry_date)->format('Y-m-d'),
                    'visa_actual_status' => 1,
                    'visa_completed'     => 1,
                    'total_completed'    => $request->mobilizeId,
                ];
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update($visaData);
                $output['messege'] = 'Visa basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 9:
                $visaOnlineData = [
                    'visa_online_id'            => $request->mobilizeId,
                    'visa_online_date'          => Carbon::parse($request->visa_online_date)->format('Y-m-d'),
                    'visa_online_status_code'   => $request->visa_status_code,
                    'visa_online_expiry_date'   => Carbon::parse($request->visa_expiry_date)->format('Y-m-d'),
                    'visa_online_actual_status' => 1,
                    'visa_online_completed'     => 1,
                    'total_completed'           => $request->mobilizeId,
                ];
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update($visaOnlineData);
                $output['messege'] = 'Visa basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 10: /*VISA PRINT*/
                // return $request->mobilize_date;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_print_id'        => $request->mobilizeId,
                        'visa_no'              => $request->visa_no,
                        'visa_print_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'visa_print_status'    => 1,
                        'visa_print_completed' => 1,
                        'total_completed'      => $request->mobilizeId,
                        'visa_print_expiry_date'=> (!empty($request->visa_print_expiry_date)? DateTime::createFromFormat('d-m-Y', $request->visa_print_expiry_date)->format('Y-m-d'):''),
                    ]);
                $output['messege'] = 'VISA PRINT basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 11: /*VISA ATTACHED*/

                $mofa_date = EwMobilizationMasterTable::valid()
                    ->where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->first()->mofa_date;

                if(empty($mofa_date) || $mofa_date == "0000-00-00 00:00:00"){

                    $output['messege'] = 'At first update MOFA  date';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;

                }else{

                    $mobilize_date = Carbon::parse($request->mobilize_date)->format('Y-m-d');
                    $visa_attached_date = new DateTime($mobilize_date);
                    $mofa_date = new DateTime($mofa_date);

                    if($visa_attached_date > $mofa_date){

                        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->where('ew_candidatescv_id', $this->candidateId)
                            ->update([
                                'visa_attached_id'        => $request->mobilizeId,
                                'visa_attached_no'        => $request->visa_no,
                                'visa_attached_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                                'visa_attached_status'    => 1,
                                'visa_attached_completed' => 1,
                                'total_completed'         => $request->mobilizeId,
                                'visa_attach_expiry_date' => (!empty($request->visa_stamp_expiry_date)? DateTime::createFromFormat('d-m-Y', $request->visa_stamp_expiry_date)->format('Y-m-d'):''),
                            ]);

                        $output['messege'] = 'VISA ATTACHED basic info has been updated';
                        $output['msgType'] = 'success';
                        $output['status'] = 1;

                    }else{

                        $output['messege'] = 'Visa Stamp should be grather than MOFA date';
                        $output['msgType'] = 'danger';
                        $output['status'] = 0;

                    }
                }

                
                return $output;
                break;
            case 12: /*PCC*/
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'pcc_id'            => $request->mobilizeId,
                        'pcc_serial_number' => $request->pcc_serial_number,
                        'pcc_date'          => Carbon::parse($request->pcc_date)->format('Y-m-d'),
                        'pcc_status'        => 1,
                        'pcc_completed'     => 1,
                        'total_completed'   => $request->mobilizeId,
                    ]);
                $output['messege'] = 'PCC basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 13: /*GTTC*/
                if($request->training_start_date == 'N/A')
                {
                    $training_start_date = " ";
                }else{
                    $training_start_date = Carbon::parse($request->training_start_date)->format('Y-m-d');
                }
                if($request->gttc_date == 'N/A')
                {
                    $gttc_date = '';
                }else{
                    $gttc_date = Carbon::parse($request->gttc_date)->format('Y-m-d');
                }
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'gttc_id'             =>  $request->mobilizeId,
                        'gttc_serial_number'  =>  $request->gttc_serial_number,
                        'training_center_name'=> $request->training_center_name,
                        'training_start_date' => $training_start_date,
                        'gttc_date'           => $gttc_date,
                        'gttc_status'         => 1,
                        'gttc_completed'      => 1,
                        'total_completed'     => $request->mobilizeId,
                    ]);
                $output['messege'] = 'GTTC basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 14: /*FINGER PRINT*/
                // return $request->mobilize_date;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'fingerprint_status_id' => $request->mobilizeId,
                        'fingerprint_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'fingerprint_status'    => 1,
                        'fingerprint_completed' => 1,
                        'total_completed'       => $request->mobilizeId,
                    ]);
                $output['messege'] = 'FINGER PRINT basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 15: /*BMET*/

                $visa_date = EwMobilizationMasterTable::valid()
                    ->where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->first()->visa_attached_date;

                if(empty($visa_date) || $visa_date == "0000-00-00 00:00:00"){

                    $output['messege'] = 'At first update VISA Attched Date';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;

                }else{
                    $mobilize_date = Carbon::parse($request->mobilize_date)->format('Y-m-d');
                    $bmet_date = new DateTime($mobilize_date);
                    $visa_date = new DateTime($visa_date);

                    if($bmet_date > $visa_date){

                        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->where('ew_candidatescv_id', $this->candidateId)
                            ->update([
                                'bmet_id'         => $request->mobilizeId,
                                'bmet_date'       => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                                'bmet_status'     => 1,
                                'bmet_completed'  => 1,
                                'total_completed' => $request->mobilizeId,
                            ]);
                        $output['messege'] = 'BMET basic info has been updated';
                        $output['msgType'] = 'success';
                        $output['status'] = 1;

                    }else{

                        $output['messege'] = 'BMET date should be grather than VISA date';
                        $output['msgType'] = 'danger';
                        $output['status'] = 0;

                    }
                }

                
                return $output;
                break;
            case 16: /*GAMCA*/
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'gamca_id'        => $request->mobilizeId,
                        'gamca_gone_date' => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'gamca_status'    => 1,
                        'gamca_completed' => 1,
                        'total_completed' => $request->mobilizeId,
                    ]);
                $output['messege'] = 'GAMCA basic info has been updated';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 17:
                // return  $request->medical_slip_status;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'medical_slip_center'    => $request->medical_name_in_slip,
                        'medical_slip_id'        => $request->mobilizeId,
                        'medical_slip_no'        => $request->medical_slip_no,
                        'medical_slip_date'      => Carbon::parse($request->medical_slip_date)->format('Y-m-d'),
                        'medical_slip_status'    => $request->medical_slip_status,
                        'medical_slip_completed' => 1,
                        'total_completed'        => $request->mobilizeId,
                    ]);
                $output['messege'] = 'Medical Slip Record Saved';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 18:
                // return  $request->medical_slip_status;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'document_sent_id'        => $request->mobilizeId,
                        'document_sent_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'document_sent_status'    => 1,
                        'document_sent_completed' => 1,
                        'total_completed'         => $request->mobilizeId,
                    ]);
                $output['messege'] = 'Document has been sent';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 19:
                $remedicalData = [
                    'remedical_id'            => $request->mobilizeId,
                    'remedical_date'          => Carbon::parse($request->remedical_date)->format('Y-m-d'),
                    'remedical_name'          => $request->remedical_name,
                    'remedical_actual_status' => $request->remedical_actual_status,
                    'remedical_status'        => 1,
                    'remedical_completed'     => 1,
                    'total_completed'         => $request->mobilizeId,
                ];

                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update($remedicalData);

                if ($request->has('remedical_call_remarks')) {
                    MobilizationActivity::create([
                        'ew_project_id'      => $this->projectId,
                        'ew_candidatescv_id' => $this->candidateId,
                        'mobilization_id'    => $mobilizeId,
                        'remarks'            => $request->remedical_call_remarks,
                    ]);
                }

                $output['messege'] = 'Medical remedical has been completed';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 20:
                // return  $request->mobilizeId;

                // $medical_status_date = EwMobilizationMasterTable::valid()
                //     ->where('ew_project_id', $this->projectId)
                //     ->where('ew_candidatescv_id', $this->candidateId)
                //     ->first()->medical_status_date;

                // if(empty($medical_status_date) || $medical_status_date == "0000-00-00 00:00:00"){
                //     $output['messege'] = 'At first update medicale status date';
                //     $output['msgType'] = 'danger';
                //     $output['status'] = 0;
                // }else{

                    // $mobilize_date = Carbon::parse($request->mobilize_date)->format('Y-m-d');
                    // $pccRcvDate = new DateTime($mobilize_date);
                    // $medical_status_date = new DateTime($medical_status_date);

                    // if($pccRcvDate >= $medical_status_date){

                        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->where('ew_candidatescv_id', $this->candidateId)
                            ->update([
                                'pcc_received_id'        => $request->mobilizeId,
                                'pcc_received_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                                'pcc_received_status'    => 1,
                                'pcc_received_completed' => 1,
                                'total_completed'        => $request->mobilizeId,
                            ]);
                        $output['messege'] = 'PCC has been received';
                        $output['msgType'] = 'success';
                        $output['status'] = 1;

                //     }else{

                //         $output['messege'] = 'PCC Rcv date should be grather than medical status date';
                //         $output['msgType'] = 'danger';
                //         $output['status'] = 0;
                //     }   
                // }

                
                return $output;
                break;
            case 21:
                // return  $request->mobilizeId;

                // $medical_status_date = EwMobilizationMasterTable::valid()
                //     ->where('ew_project_id', $this->projectId)
                //     ->where('ew_candidatescv_id', $this->candidateId)
                //     ->first()->medical_status_date;

                // if(empty($medical_status_date) || $medical_status_date == "0000-00-00 00:00:00"){
                //     $output['messege'] = 'At first update medicale status date';
                //     $output['msgType'] = 'danger';
                //     $output['status'] = 0;
                // }else{

                if($request->mobilize_date == 'N/A')
                {
                    $mobilize_date = " ";
                }else{
                    $mobilize_date = Carbon::parse($request->mobilize_date)->format('Y-m-d');
                }

                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                ->where('ew_candidatescv_id', $this->candidateId)
                ->update([
                    'gttc_received_id'        => $request->mobilizeId,
                    'gttc_received_date'      => $mobilize_date,
                    'gttc_received_status'    => 1,
                    'gttc_received_completed' => 1,
                    'total_completed'         => $request->mobilizeId,
                ]);
                $output['messege'] = 'GTC has been received';
                $output['msgType'] = 'success';
                $output['status'] = 1;

                //     }else{
                //         $mobilize_date = Carbon::parse($request->mobilize_date)->format('Y-m-d');
                //         $gtcRcvDate = new DateTime($mobilize_date);
                //         $medical_status_date = new DateTime($medical_status_date);

                //         if($gtcRcvDate >= $medical_status_date){

                //             EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                //                 ->where('ew_candidatescv_id', $this->candidateId)
                //                 ->update([
                //                     'gttc_received_id'        => $request->mobilizeId,
                //                     'gttc_received_date'      => $mobilize_date,
                //                     'gttc_received_status'    => 1,
                //                     'gttc_received_completed' => 1,
                //                     'total_completed'         => $request->mobilizeId,
                //                 ]);

                //             $output['messege'] = 'GTC has been received';
                //             $output['msgType'] = 'success';
                //             $output['status'] = 1;
                //         }else{
                //             $output['messege'] = 'GTC Rcv date should be grather than medical status date';
                //             $output['msgType'] = 'danger';
                //             $output['status'] = 0;
                //         }
                //     }
                // }
                return $output;
                break;
            case 22:
                // return  $request->mobilizeId;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'smartcard_id'        => $request->mobilizeId,
                        'smartcard_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'smartcard_status'    => 1,
                        'smartcard_completed' => 1,
                        'total_completed'     => $request->mobilizeId,
                    ]);
                $output['messege'] = 'Smart Card has been received';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 23:
                // return  $request->mobilizeId;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'pta_request_id'        => $request->mobilizeId,
                        'pta_request_date'      => Carbon::parse($request->pta_request_date)->format('Y-m-d'),
                        'pta_request_status'    => $request->pta_request_status,
                        'pta_request_completed' => 1,
                        'total_completed'       => $request->mobilizeId,
                    ]);
                $output['messege'] = 'PTA request has been completed!';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 24:
                // return $request->pta_receive_remarks;

                $bmet_date = EwMobilizationMasterTable::valid()
                    ->where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->first()->bmet_date;

                if(empty($bmet_date) || $bmet_date == "0000-00-00 00:00:00"){

                    $output['messege'] = 'At first update BMET Date';
                    $output['msgType'] = 'danger';
                    $output['status'] = 0;

                }else{
                    $flight_date = Carbon::parse($request->pta_flight_date)->format('Y-m-d');
                    $flight_date = new DateTime($flight_date);
                    $bmet_date = new DateTime($bmet_date);

                    if($flight_date > $bmet_date){

                        EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                            ->where('ew_candidatescv_id', $this->candidateId)
                            ->update([
                                'pta_received_id'        => $request->mobilizeId,
                                'pta_received_date'      => Carbon::parse($request->pta_receive_date)->format('Y-m-d'),
                                'flight_date'            => Carbon::parse($request->pta_flight_date)->format('Y-m-d'),
                                'flight_no'              => $request->flight_no,
                                'flight_time'            => $request->flight_time,
                                'transit_place'          => $request->transit_place,
                                'pta_received_status'    => 1,
                                'pta_received_completed' => 1,
                                'total_completed'        => $request->mobilizeId,
                            ]);

                        $output['messege'] = 'PTA Receive has been completed!';
                        $output['msgType'] = 'success'; 
                        $output['status'] = 1;

                    }else{

                        $output['messege'] = 'Flight date should be grather than BMET date';
                        $output['msgType'] = 'danger';
                        $output['status'] = 0;
                    }
                }

                
                return $output;
                break;
            case 25:
                // return $request->transit_place;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'flight_id'        => $request->mobilizeId,
                        'flight_status'    => $request->flight_status,
                        'flight_completed' => 1,
                        'total_completed'  => $request->mobilizeId,
                    ]);

                MobilizationActivity::create([
                    'ew_project_id'      => $this->projectId,
                    'ew_candidatescv_id' => $this->candidateId,
                    'mobilization_id'    => $request->mobilizeId,
                    'remarks'            => $request->flight_remarks,
                ]);

                $output['messege'] = 'Flight has been created!';
                $output['msgType'] = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 26:
                // return $request->flight_briefing_date;
                $flight_brief_date = Carbon::parse($request->flight_briefing_date)->format('Y-m-d');
                $flight_date = EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                                     ->where('ew_candidatescv_id', $this->candidateId)
                                     ->first()->flight_date;

                $flight_date = Carbon::parse($flight_date)->format('Y-m-d');

                if($flight_brief_date > $flight_date)
                {
                    $output['messege'] = 'NOT OK';
                    $output['msgType'] = 'error';
                    echo "it's flight brief date greater then flight date";
                }
                else
                {
                    EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'flight_briefing_id'        => $request->mobilizeId,
                        'flight_briefing_date'      => Carbon::parse($request->flight_briefing_date)->format('Y-m-d'),
                        'flight_briefing_status'    => 1,
                        'flight_briefing_completed' => 1,
                        'total_completed'           => $request->mobilizeId,
                    ]);

                    MobilizationActivity::create([
                        'ew_project_id'      => $this->projectId,
                        'ew_candidatescv_id' => $this->candidateId,
                        'mobilization_id'    => $request->mobilizeId,
                        'remarks'            => $request->flight_remarks,
                    ]);

                    $output['messege'] = 'Flight has been created!';
                    $output['msgType'] = 'success';
                    $output['status'] = 1;

                }
                return $output;
                break;
            case 27:
                // return  $request->mobilizeId;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'qvc_appointment_id'        => $request->mobilizeId,
                        'qvc_appointment_date'      => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                        'qvc_appointment_status'    => 1,
                        'qvc_appointment_completed' => 1,
                        'total_completed'           => $request->mobilizeId,
                    ]);

                $output['messege'] = 'QVC Appointment has been completed!';
                $output['msgType'] = 'success';
                $output['status'] = 1;

                return $output;

                break;
            case 28:
                EwCandidatesCV::where('id', $this->candidateId)
                ->where('ew_project_id', $this->projectId)
                ->update([
                'approved_status' => 1,
                'approved_date'   => Carbon::parse($request->mobilize_date)->format('Y-m-d'),
                ]);

                $output['messege'] = Helper::single_candidate($request->candidateId)->full_name . ' is approved!';
                $output['msgType'] = 'success';
                $output['status'] = 1;

                return $output;
                break;
            case 29: /*MEDICAL CALL*/
                // return $request->gttc_serial_number;
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'medical_call_id'        => $request->mobilizeId,
                        'medical_call_status'    => 1,
                        'medical_call_completed' => 1,
                        'medical_call_date'      => Carbon::parse($request->medical_call_date)->format('Y-m-d'),
                        'total_completed'        => $request->mobilizeId,
                    ]);

                if ($request->has('pcc_sent')) {
                    EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->where('ew_candidatescv_id', $this->candidateId)
                        ->update([
                            'pcc_id'            => 12,
                            'pcc_date'          => Carbon::parse($request->pcc_dates)->format('Y-m-d'),
                            'pcc_serial_number' => $request->pcc_serial_numbers,
                            'pcc_status'        => 1,
                            'pcc_completed'     => 1,
                            'total_completed'   => $request->mobilizeId,
                        ]);
                }

                if ($request->has('gtc_sent')) {
                    EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->where('ew_candidatescv_id', $this->candidateId)
                        ->update([
                            'gttc_id'            => 13,
                            'gttc_date'          => Carbon::parse($request->gttc_dates)->format('Y-m-d'),
                            'gttc_serial_number' => $request->gttc_serial_numbers,
                            'gttc_status'        => 1,
                            'gttc_completed'     => 1,
                            'total_completed'    => $request->mobilizeId,
                        ]);
                }

                $table                     = new MobilizationActivity;
                // $table->activities_type = $request->activities_type;
                $table->ew_project_id      = $this->projectId;
                $table->ew_candidatescv_id = $this->candidateId;
                $table->mobilization_id    = $request->mobilizeId;
                // $table->call_type       = $request->call_type;
                // $table->call_date       = $actualCallDate;
                // $table->invite_date     = $inviteDate;
                $table->call_date          = Carbon::parse($request->medical_call_date)->format('Y-m-d');
                $table->remarks            = $request->medical_call_remarks;
                $table->save();

                $output['messege']         = 'Medical call record has been saved!';
                $output['msgType']         = 'success';
                $output['status'] = 1;
                return $output;
                break;
            case 30:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'medical_sent_id'        => $request->mobilizeId,
                        'medical_sent_date'      => Carbon::parse($request->medical_sent_date)->format('Y-m-d'),
                        'medical_sent_status'    => 1,
                        'medical_sent_completed' => 1,
                        'total_completed'        => $request->mobilizeId,
                    ]);

                if ($request->has('medical_slip')) {
                    // return $request->medical_status_for_slip;
                    EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                        ->where('ew_candidatescv_id', $this->candidateId)
                        ->update([
                            'medical_slip_id'        => 17,
                            'medical_name'           => $request->medical_center_name_for_slip,
                            'medical_slip_no'        => $request->medicla_no_for_slip,
                            'medical_slip_status'    => $request->medical_status_for_slip,
                            'medical_slip_completed' => 1,
                            'medical_slip_date'      => Carbon::parse($request->medical_date_for_slip)->format('Y-m-d'),
                            'total_completed'        => $request->mobilizeId,
                        ]);
                }

                $output['messege'] = 'Medical sent record has been saved!';
                $output['msgType'] = 'success';
                $output['status'] = 1;

                return $output;
        }
    }


    public function medicalType(Request $request)
    {
        $data['projectId']   = $projectId               = $this->projectId;
        $data['candidateId'] = $candidateId             = $this->candidateId;
        $data['mobilizeId']  = $mobilizeId              = $request->mobilizeId;

        $data['medicalData'] = EwMobilizationMasterTable::valid()
        ->where('ew_project_id', $projectId)
        ->where('ew_candidatescv_id', $candidateId)
        ->first();

        return view('recruitment.mobilization.medicalType', $data);
    }

    public function medicalStoreData(Request $request)
    {

    }

    public function visaType(Request $request)
    {
        $data['projectId']     = $projectId               = $this->projectId;
        $data['candidateId']   = $candidateId             = $this->candidateId;
        $data['mobilizeId']    = $mobilizeId              = $request->mobilizeId;

        $data['jobCategories'] = EwVisaJobCategory        ::valid()->get();

        $data['visaData']      = EwMobilizationMasterTable::valid()
        ->where('ew_project_id', $projectId)
        ->where('ew_candidatescv_id')
        ->first();

        return view('recruitment.mobilization.visaType', $data);
    }

    public function visaStoreData(Request $request)
    {

    }

    public function getIncompletedMObilizeCandidate(Request $request)
    {
        return $request->all();

    }

    public function mobilizationList()
    {
        $mobilizeList = EwMobilization::valid()->get();
        return $mobilizeList;
    }

    public function mobilizationActivities(Request $request)
    {
        $data['projectId']           = $this->projectId;
        $data['candidateId']         = $this->candidateId;
        $data['mobilizeId']          = $request->mobilizeId;
        
        $data['callActivityDetails'] = MobilizationActivity::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
            ->get();

        $data['mobilizeLists']       = self::mobilizationList();

        return view('recruitment.mobilization.mobilizationActivities', $data);
    }

    public function selectedMobilizeDetails(Request $request)
    {
        $data['projectId']           = $this->projectId;
        $data['candidateId']         = $request->candidateIds;

        $data['callActivityDetails'] = MobilizationActivity::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
            ->get();

        $data['mobilizeLists']       = self::mobilizationList();

        return view('recruitment.mobilization.selectedMobilizeDetails', $data);
    }

    public function activitiesCall(Request $request)
    {
        $data['projectId']     = $this->projectId;
        $data['candidateId']   = $this->candidateId;
        $data['mobilizeId']    = $request->mobilizeId;
        $data['mobilizeLists'] = self::mobilizationList();

        return view('recruitment.mobilization.activitiesCall', $data);
    }

    public function activitiesCallDetails(Request $request)
    {
        $data['projectId']           = $this->projectId;
        $data['candidateId']         = $this->candidateId;
        $data['mobilizeId']          = $request->mobilizeId;

        $data['callActivityDetails'] = MobilizationActivity::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
            ->get();

        $data['mobilizeLists'] = self::mobilizationList();

        return view('recruitment.mobilization.activitiesCallDetails', $data);
    }

    public function activitiesDirectContactDetails(Request $request)
    {
        $data['projectId']           = $this->projectId;
        $data['candidateId']         = $this->candidateId;
        $data['mobilizeId']          = $request->mobilizeId;

        $data['callActivityDetails'] = MobilizationActivity::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
            ->get();

        $data['mobilizeLists']       = self::mobilizationList();

        return view('recruitment.mobilization.activitiesDirectContactDetails', $data);
    }

    public function activitiesNoteDetails(Request $request)
    {
        $data['projectId']           = $this->projectId;
        $data['candidateId']         = $this->candidateId;
        $data['mobilizeId']          = $request->mobilizeId;

        $data['callActivityDetails'] = MobilizationActivity::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
            ->get();
        $data['mobilizeLists']       = self::mobilizationList();

        return view('recruitment.mobilization.activitiesNoteDetails', $data);
    }

    /**
     * Mobilization Activities
     */
    public function activitiesCallStore(Request $request)
    {

        $callDate                  = Carbon::parse($request->call_date);
        $inviteDate                = Carbon::parse($request->invite_date);
        $actualCallDate            = $callDate->toDateString();

        if ($request->mobilizeId   == "{mobilizeId}") {
            $mobilizeId            = $request->call_type;
        } else {
            $mobilizeId            = $request->mobilizeId;
        }

        $table                     = new MobilizationActivity;
        $table->activities_type    = $request->activities_type;
        $table->ew_project_id      = $this->projectId;
        $table->ew_candidatescv_id = $this->candidateId;
        $table->mobilization_id    = $mobilizeId;
        $table->call_type          = $request->call_type;
        $table->call_date          = $actualCallDate;
        $table->invite_date        = $inviteDate;
        $table->remarks            = $request->remarks;
        $table->save();

        $output['msgType']         = 'success';
        $output['messege']         = 'Phone contact has been created for ' . Helper::single_mobilization($mobilizeId)->name;

        return $output;
    }

    public function activitiesDirectContact(Request $request)
    {
        $data['projectId']     = $this->projectId;
        $data['candidateId']   = $this->candidateId;
        $data['mobilizeId']    = $request->mobilizeId;
        $data['mobilizeLists'] = self::mobilizationList();

        return view('recruitment.mobilization.activitiesDirectContact', $data);
    }
    public function activitiesDirectContactStore(Request $request)
    {
        $callDate = Carbon::parse($request->call_date);
        $actualCallDate = $callDate->toDateString();

        if ($request->mobilizeId == "{mobilizeId}") {
            $mobilizeId = $request->call_type;
        } else {
            $mobilizeId = $request->mobilizeId;
        }

        $table                     = new MobilizationActivity;
        $table->activities_type    = $request->activities_type;
        $table->ew_project_id      = $this->projectId;
        $table->ew_candidatescv_id = $this->candidateId;
        $table->mobilization_id    = $mobilizeId;
        $table->call_type          = $request->call_type;
        $table->call_date          = $actualCallDate;
        $table->remarks            = $request->remarks;
        $table->save();

        $output['messege']         = 'Direct contact has been created for ' . Helper::single_mobilization($mobilizeId)->name;
        $output['msgType']         = 'success';

        return $output;
    }

    public function activitiesNote(Request $request)
    {
        $data['projectId']     = $this->projectId;
        $data['candidateId']   = $this->candidateId;
        $data['mobilizeId']    = $request->mobilizeId;
        $data['mobilizeLists'] = self::mobilizationList();

        return view('recruitment.mobilization.activitiesNote', $data);
    }
    public function activitiesNoteStore(Request $request)
    {

        $callDate = Carbon::parse($request->call_date);
        $actualCallDate = $callDate->toDateString();

        if ($request->mobilizeId == "{mobilizeId}") {
            $mobilizeId = $request->call_type;
        } else {
            $mobilizeId = $request->mobilizeId;
        }

        $table                     = new MobilizationActivity;
        $table->note_subject       = $request->note_subject;
        $table->activities_type    = $request->activities_type;
        $table->ew_project_id      = $this->projectId;
        $table->ew_candidatescv_id = $this->candidateId;
        $table->mobilization_id    = $mobilizeId;
        $table->call_type          = $request->call_type;
        $table->call_date          = $actualCallDate;
        $table->remarks            = $request->remarks;
        $table->save();

        $output['messege']         = 'Note activity has been created for ' . Helper::single_mobilization($mobilizeId)->name;
        $output['msgType']         = 'success';

        return $output;
    }

    public function activitiesSideNote(Request $request)
    {
        $data['projectId']   = $this->projectId;
        $data['candidateId'] = $this->candidateId;
        $data['mobilizeId']  = $request->mobilizeId;

        $data['callActivityDetails'] = MobilizationActivity::where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
            ->where('activities_type', 3)
            ->limit(1)
            ->get();

        return view('recruitment.mobilization.activitiesSideNote', $data);
    }

    public function activitiesSideNoteList(Request $request)
    {
        $data['inputData']   = $request->all();
        $data['candidateId'] = $this->candidateId;
        $data['projectId']   = $this->projectId;
        $data['mobilizeId']  = $request->mobilizeId;

        return view('recruitment.mobilization.activitiesSideNoteList', $data);
    }

    public function activitiesSideNoteData(Request $request)
    {
        $data['candidateId'] = $this->candidateId;
        $data['projectId']   = $this->projectId;
        $data['mobilizeId']  = $request->mobilizeId;

        $data           = $request->all();
        $search         = $request->search;
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['id', 'updated_at']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $notes = MobilizationActivity::valid()
            ->where('ew_project_id', $this->projectId)
            ->where('ew_candidatescv_id', $this->candidateId)
            ->where('mobilization_id', $request->mobilizeId)
        //->where('activities_type', 3)
            ->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('updated_at', 'LIKE', '%' . $search . '%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        $data['allNotes'] = $notes;

        return view('recruitment.mobilization.activitiesSideNoteData', $data);
    }

    public function activitiesSideAttachment(Request $request)
    {

        return view('recruitment.mobilization.activitiesSideAttachment');
    }

    /**
     * THIS FUNCTION EXECUTE AFTER CLICK ON MOBILIZATION COMPLETE BUTTON
     * DATA COMES FROM AJAX REQUEST, CHECK: singleCandidateMobilizationData blade.
     * @param $candidateId
     * @param $completedStatus
     * @param $mobilizeId
     * @param $projectId
     */
    public function mobilizationSigleCompletedStatus(Request $request)
    {

        // candidateId: "5"
        // completedStatus: "1"
        // mobilizeId: "16"
        // projectId: "53"
        // return $request->all();

        $mobilize = $request->mobilizeId;

        switch ($mobilize) {
            case 2:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'medical_online_completed' => 1,
                        'medical_online_id'        => $mobilize,
                        'total_completed'          => $request->completedStatus,
                    ]);

                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';

                return $output;
                break;
            case 3:
                //      EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                //     ->where('ew_candidatescv_id', $this->candidateId)
                //     ->update([
                //      'medical_completed' => 1,
                //      'total_completed'   => $request->completedStatus
                // ]);
                //      $output['messege']         = 'Mobilization completed status has been created';
                //      $output['msgType']         = 'success';
                //      return $output;
                break;
            case 4:

                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'fit_card_received_completed' => 1,
                        'fit_card_id'                 => $mobilize,
                        'total_completed'             => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 5:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'mofa_completed'  => 1,
                        'mofa_id'         => $mobilize,
                        'total_completed' => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 6:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_document_sent_completed' => 1,
                        'visa_document_sent_id'        => $mobilize,
                        'total_completed'              => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 7:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'embassy_submission_completed' => 1,
                        'embassy_submission_id'        => $mobilize,
                        'total_completed'              => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 8:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_completed'  => 1,
                        'visa_id'         => $mobilize,
                        'total_completed' => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 9:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_online_completed' => 1,
                        'visa_online_id'        => $mobilize,
                        'total_completed'       => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 10:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_print_completed' => 1,
                        'visa_print_id'        => $mobilize,
                        'total_completed'      => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 11:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'visa_attached_completed' => 1,
                        'visa_attached_id'        => $mobilize,
                        'total_completed'         => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 12:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'pcc_completed'   => 1,
                        'pcc_id'          => $mobilize,
                        'total_completed' => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 13:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'gttc_completed'  => 1,
                        'gttc_id'         => $mobilize,
                        'total_completed' => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 14:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'fingerprint_completed' => 1,
                        'fingerprint_status_id' => $mobilize,
                        'total_completed'       => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 15:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'bmet_completed'  => 1,
                        'bmet_id'         => $mobilize,
                        'total_completed' => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;
            case 16:
                EwMobilizationMasterTable::where('ew_project_id', $this->projectId)
                    ->where('ew_candidatescv_id', $this->candidateId)
                    ->update([
                        'gamca_completed' => 1,
                        'gamca_id'        => $mobilize,
                        'total_completed' => $request->completedStatus,
                    ]);
                $output['messege'] = 'Mobilization completed status has been created';
                $output['msgType'] = 'success';
                return $output;
                break;

            default:

                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['tradeAddAccess'] = Helper::userAccess('trades.create', 'ew');
        $data['trades'] = EwTrades::valid()->get();
        $data['countries'] = DB::table('countries')->get();

        return view('recruitment.mobilization.create', $data);
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
        DB::beginTransaction();
        $output       = array();
        $input        = $request->all();
        $project_name = $request->project_name;
        $input_trades = $request->trade_id;

        $validator = Validator::make($input, [
            'project_name' => 'required',
        ]);

        if ($validator->passes()) {
            EwProjects::create(['project_name' => $project_name]);
            $project_id = EwProjects::valid()->orderBy('id', 'desc')->first()->id;

            foreach ($input_trades as $trade_id) {
                EwProjectTrades::create(['ew_project_id' => $project_id, 'trade_id' => $trade_id]);
            }
            $output['messege'] = 'Project has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        return $output;
        DB::commit();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['project']         = EwProjects::valid()->find($id);
        $data['tradeAddAccess']  = Helper::userAccess('trades.create', 'ew');
        $data['selected_trades'] = EwProjectTrades::valid()->select('trade_id')
            ->where('ew_project_id', $id)
            ->get()->pluck('trade_id')
            ->all();

        $data['trades'] = EwTrades::valid()->get();

        return view('recruitment.mobilization.update', $data);
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
        DB::beginTransaction();
        $output       = array();
        $input        = $request->all();
        $project_name = $request->project_name;
        $input_trades = $request->trade_id;

        $validator = Validator::make($input, [
            'project_name' => 'required',
        ]);

        if ($validator->passes()) {
            EwProjects::find($id)->update(['project_name' => $project_name]);
            $selected_trades = collect(EwProjectTrades::valid()
                    ->where('ew_project_id', $id)
                    ->get()
                    ->pluck('trade_id')
                    ->all());

            $trade_diff = $selected_trades->diff($input_trades);

            if (!empty($trade_diff)) {
                $project_selected_trades = EwProjectTrades::valid()
                    ->whereIn('trade_id', $trade_diff)
                    ->where('ew_project_id', $id)
                    ->get();

                foreach ($project_selected_trades as $project_selected_trade) {
                    EwProjectTrades::valid()->find($project_selected_trade->id)->delete();
                }
            }

            if (!empty($input_trades)) {
                foreach ($input_trades as $trade_id) {
                    if (!$selected_trades->contains($trade_id)) {
                        EwProjectTrades::create(['ew_project_id' => $id, 'trade_id' => $trade_id]);
                    }
                }
            }

            $output['messege'] = 'Project has been updated';
            $output['msgType'] = 'success';

        } else {
            $output = Helper::vError($validator);
        }

        return $output;
        DB::commit();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        EwProjects::valid()->find($id)->delete();
    }

    /**
     * Display mobilization list from project configuration
     * @param ew_project_id, $project_id
     * @author Shere Ali
     * Date: 24-10-2018
     * @method
     */
    public function configure($project_id)
    {
        $configures = EwConfiguration::where('ew_project_id', $project_id)->first();

        if (empty($configures)) {

            $data['mobilizations'] = EwMobilization::valid()->get();

        } else {

            $data['configurations'] = $conId        = json_decode($configures->mobilization_id, true);
            $data['mobilizations']  = EwMobilization::valid()
                ->whereNotIn('id', $conId)
                ->get();

        }

        $data['project_id'] = $project_id;

        return view('recruitment.mobilization.configure', $data);
    }

    public function storeConfiguration(Request $request)
    {
        $user_id             = Auth           ::user()->get()->id;
        $project_id          = $request->project_id;
        $moblizationIds      = $request->mobilization_id;
        $mobilization_id     = json_encode($moblizationIds);
        $checkConfigureTable = EwConfiguration::where('ew_project_id', $project_id)->first();

        if (empty($checkConfigureTable)) {

            EwConfiguration::create([
                'ew_project_id' => $project_id,
                'mobilization_id' => $mobilization_id,
            ]);

            EwProjects::where('id', $project_id)->update(['configuration' => 1]);

        } else {

            EwConfiguration::where('ew_project_id', $project_id)
                ->update([
                    'mobilization_id' => $mobilization_id,
                    'updated_at' => date("Y-m-d H:i:s"),
                    'updated_by' => $user_id,
                ]);
            
            EwProjects::where('id', $project_id)->update(['configuration' => 1]);

        }

        $output['messege'] = 'Configuration has been updated';
        $output['msgType'] = 'success';

        return $output;
    }

    public function geCountry()
    {
        $countries = DB::table('countries')->get();
    }
}
