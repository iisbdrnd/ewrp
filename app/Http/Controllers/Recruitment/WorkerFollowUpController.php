<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use PDF;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Project;
use App\Model\EwProjects;
use App\Model\EwCandidates;
use App\Model\EwTrades;
use App\Model\EwReferences;
use App\Model\EwCandidatesCV;
use App\Model\EwMobilization;
use App\Model\EwProjectTrades;


class WorkerFollowUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    //-----------CANDIDATE LIST----------
        //Candidate Report
        public function workerFollowUpReport(Request $request){

            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()
                ->where('assign_user', '!=', '') 
                ->orderBy('id', 'DSC')   
                ->get();

            return view('recruitment.reports.workerFollowUp.workerFollowUpReport', $data);
        }

        public function projectWiseDealer(Request $request){

            $data = $request->all();
            $project_id = $request->ew_project_id;
            
            $data['assignDealer'] = $assignDealer = EwProjects::valid() 
                ->where('id', $project_id)
                ->first()->assign_user;

            $assignDealer = json_decode($assignDealer, true);
            $data['dealers'] = DB::table('users')
                ->where('valid', 1)
                ->where('email', '!=', 'admin@iisbd.com')
                ->whereIn('id', $assignDealer)
                ->get();

            return view('recruitment.reports.workerFollowUp.dealerList', $data);
        }

        public function workerFollowUpReportView(Request $request) {

            $data = $request->all();
            $byProjectId = $param['byProjectId'] = $request->byProjectId;
            $byDealerId = $param['byDealerId'] = $request->byDealerId;
            $data = self::getCandidateList($param);
            $pdf_url = route('recruit.workerFollowUpPdf').'?byProjectId='.$byProjectId. '&byDealerId='.$byDealerId;
            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('recruitment.reports.workerFollowUp.workerFollowUpReportView', $data);
        }

        //Worker Report View PDF
        public function workerFollowUpReportPdf(Request $request) {

            $byProjectId = $param['byProjectId'] = $request->byProjectId;
            $byDealerId = $param['byDealerId'] = $request->byDealerId;

            $data = self::getCandidateList($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('recruitment.reports.workerFollowUp.workerFollowUpReportView', $data);
            $file_name = 'worker-list-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Worker List Data
        public static function getCandidateList($param) {
            $byProjectId = $data['byProjectId'] = $param['byProjectId'];
            $byDealerId = $data['byDealerId'] = $param['byDealerId'];

            $paginate = Helper::paginate($data);
            $data['sn'] = $paginate->serial;

            $data['tradeDetails'] = EwProjectTrades::valid()
                ->where('ew_project_id', $byProjectId)
                ->get();

            if($byProjectId > 0){
                $data['workerDeatails'] = EwCandidatesCV::join('ew_mobilization_master_tables', 'ew_mobilization_master_tables.ew_candidatescv_id','=', 'ew_candidatescv.id')
                    ->select('ew_candidatescv.id', 'ew_candidatescv.ew_project_id', 'ew_candidatescv.full_name','ew_candidatescv.passport_no','ew_candidatescv.reference_id','ew_candidatescv.selected_trade', 'ew_candidatescv.approved_date','ew_mobilization_master_tables.medical_gone_date','ew_mobilization_master_tables.medical_status_date','ew_mobilization_master_tables.mofa_date','ew_mobilization_master_tables.visa_attached_date','ew_mobilization_master_tables.pcc_received_date','ew_mobilization_master_tables.gttc_received_date','ew_mobilization_master_tables.bmet_date','ew_mobilization_master_tables.flight_date', 'ew_mobilization_master_tables.medical_sent_date','ew_mobilization_master_tables.smartcard_date')
                    ->where('ew_candidatescv.ew_project_id', $byProjectId)
                    ->where('ew_mobilization_master_tables.ew_project_id', $byProjectId)
                    ->where(function($query) use ($byDealerId){
                        if ($byDealerId != 0) {
                            $query->where('ew_candidatescv.dealer_id', $byDealerId);
                        }
                    })
                    ->where('ew_candidatescv.approved_status', 1)
                    ->where('ew_candidatescv.result', 1)
                    ->where('ew_candidatescv.valid', 1)
                    ->get();
            }else{
                $data['workerDeatails'] = [];
            }




            return $data;
        }

}
