<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use PDF;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwCollectableAccountHeads;
use App\Model\EwProjectCollectableSelection;
use App\Model\Project;
use App\Model\EwProjects;
use App\Model\EwCandidates;
use App\Model\EwTrades;
use App\Model\EwReferences;
use App\Model\EwCandidateTransaction;


class CandidateReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    //-----------CANDIDATE LIST----------
        //Candidate Report
        public function candidateReport(Request $request){
            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()->get();
            $data['ewTrades'] = EwTrades::valid()->get();
            $data['ewReferences'] = EwReferences::valid()->get();

            return view('ew.reports.candidateReports.candidateReport', $data);
        }

        //Candidate Report View
        public function candidateReportView(Request $request) {
            $data = $request->all();
            $data['previewType'] = $previewType = $param['previewType'] = $request->previewType;
            $byProjectId = $param['byProjectId'] = $request->byProjectId;
            $byReferenceId = $param['byReferenceId'] = $request->byReferenceId;
            $byTradeId = $param['byTradeId'] = $request->byTradeId;

            $data = self::getCandidateList($param);
            $pdf_url = route('ew.candidateListPdf').'?previewType='.$previewType. '&byProjectId='.$byProjectId. '&byReferenceId='.$byReferenceId. '&byTradeId='.$byTradeId;

            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('ew.reports.candidateReports.candidateReportView', $data);
        }

        //Candidate Report View PDF
        public function candidateListPdf(Request $request) {
            $param['previewType'] = $request->previewType;
            $param['byProjectId'] = $request->byProjectId;
            $param['byReferenceId'] = $request->byReferenceId;
            $param['byTradeId'] = $request->byTradeId;

            $data = self::getCandidateList($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('ew.reports.candidateReports.candidateReportView', $data);
            $file_name = 'candidate-list-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Candidate List Data
        public static function getCandidateList($param) {
            $previewType = $data['previewType'] = $param['previewType'];
            $byProjectId = $data['byProjectId'] = $param['byProjectId'];
            $byReferenceId = $data['byReferenceId'] = $param['byReferenceId'];
            $byTradeId = $data['byTradeId'] = $param['byTradeId'];

            $paginate = Helper::paginate($data);
            $data['sn'] = $paginate->serial;

            $candidateReports = EwCandidates::where('ew_candidates.valid', 1)
                ->where(function($query) use ($byProjectId, $byReferenceId, $byTradeId){
                    if($byProjectId>0) { $query->where('ew_candidates.ew_project_id', $byProjectId); }
                    if($byReferenceId>0) { $query->where('ew_candidates.reference_id', $byReferenceId); }
                    if($byTradeId>0) { $query->where('ew_candidates.trade_id', $byTradeId); }
                })
                ->orderBy('ew_candidates.candidate_id', 'asc');

            switch ($previewType) {
                case 2:
                    # project
                    $data['candidateReports'] = $candidateReports->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                        ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                        ->select('ew_candidates.*', 'ew_references.reference_name', 'ew_trades.trade_name')
                        ->get()->groupBy('ew_project_id');

                    $ewProjects = ($byProjectId>0)? EwProjects::valid()->where('id', $byProjectId) : EwProjects::valid();
                    $data['ewProjects'] = $ewProjects->get()->keyBy('id');
                    break;
                case 3:
                    # Reference
                    $data['candidateReports'] = $candidateReports->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                        ->join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                        ->select('ew_candidates.*', 'ew_trades.trade_name', 'ew_projects.project_name')
                        ->get()->groupBy('reference_id');

                    $references = ($byReferenceId>0)? EwReferences::valid()->where('id', $byReferenceId) : EwReferences::valid();
                    $data['references'] = $references->get()->keyBy('id');
                    break;
                case 4:
                    # Trade
                    $data['candidateReports'] = $candidateReports->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                        ->join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                        ->select('ew_candidates.*', 'ew_references.reference_name', 'ew_projects.project_name')
                        ->get()->groupBy('trade_id');

                    $trades = ($byTradeId>0)? EwTrades::valid()->where('id', $byTradeId) : EwTrades::valid();
                    $data['trades'] = $trades->get()->keyBy('id');
                    break;
                default:
                    # All
                    $data['candidateReports'] = $candidateReports->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                        ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                        ->join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                        ->select('ew_candidates.*', 'ew_references.reference_name', 'ew_trades.trade_name', 'ew_projects.project_name')
                        ->get();
                    break;
            }

            return $data;
        }



    //-----------CANDIDATE FLIGHT REPORT----------
        //Candidate Flight Report
        public function candidateFlightReport(Request $request)
        {
            $data['inputData'] = $request->all();
            $data['projects'] = EwProjects::valid()->get();
            $data['references'] = EwReferences::valid()->get();

            return view('ew.reports.candidateFlightReport.candidateFlightReport', $data);
        }

        //Candidate Flight Report View
        public function candidateFlightReportData(Request $request) {
            $project_id = $param['project_id'] = $request->project;
            $reference_id = $param['reference_id'] = $request->reference_id;
            $data['date_range'] = $date_range = $param['date_range'] = $request->date_range;
            $data['from_date'] = $from_date = $param['from_date'] = $request->from_date;
            $data['to_date'] = $to_date = $param['to_date'] = $request->to_date;

            $data = self::getCandidateFlightReport($param);

            $pdf_url = route('ew.candidateFlightReportPdf').'?project='.$project_id.'&date_range='.$date_range. '&from_date='.$from_date. '&to_date='.$to_date. '&reference_id='.$reference_id;

            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('ew.reports.candidateFlightReport.candidateFlightReportData', $data);
        }


        //Candidate Flight Report PDF
        public function candidateFlightReportPdf(Request $request) {
            $param['project_id'] = $request->project;
            $param['date_range'] = $request->date_range;
            $param['from_date'] = $request->from_date;
            $param['to_date'] = $request->to_date;

            $data = self::getCandidateFlightReport($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('ew.reports.candidateFlightReport.candidateFlightReportData', $data);
            $file_name = 'candidate-flight-report-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Candidate Flight Report Data
        public static function getCandidateFlightReport($param) {
            $project_id = $data['project_id'] = $param['project_id'];
            $reference_id = $data['reference_id'] = $param['reference_id'];
            $date_range = $data['date_range'] = $param['date_range'];
            $from_date = $data['from_date'] = $param['from_date'];
            $to_date = $data['to_date'] = $param['to_date'];


            $paginate = Helper::paginate($data);
            $data['sn'] = $paginate->serial;

            if($date_range) {
                $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
                $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
            }

            if ($project_id) {
                $data['companyInfo'] = EwProjects::valid()->find($project_id);
            }

            $data['flightReports'] = EwCandidates::join('ew_flights', 'ew_candidates.id', '=', 'ew_flights.candidate_id')
                ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                ->select('ew_candidates.*', 'ew_flights.flight_no', 'ew_flights.remarks as flight_remarks', 'ew_references.reference_name', 'ew_trades.trade_name')
                ->where(function($query) use ($project_id){
                    if($project_id>0) {
                        $query->where('ew_candidates.ew_project_id', $project_id);
                    }
                })
                ->where(function($query) use ($reference_id){
                    if($reference_id>0) {
                        $query->where('ew_candidates.reference_id', $reference_id);
                    }
                })
                ->where(function($query) use ($from_date, $to_date, $date_range){
                    if ($date_range) {
                        if($from_date && $to_date) {
                            $query->orWhereBetween('ew_candidates.candidate_flight_date', [$from_date, $to_date]);
                        }
                    }
                })
                ->where('ew_flights.flight_status', 1)
                ->where('ew_candidates.flight_status', 1)
                ->where('ew_candidates.valid', 1)
                ->get();

            return $data;
        }


    //-----------CANDIDATE LEDGER BOOK REPORT----------
        //Candidate Ledger Book
        public function candidateLedgerBook(Request $request)
        {
            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()->get();
            $data['candidates'] = EwCandidates::valid()->get();

            return view('ew.reports.candidateLedgerBook.index', $data);
        }

        //Candidate Ledger Book Data
        public function candidateLedgerBookData(Request $request) {
            $candidate_id = $param['candidate_id'] = $request->candidate;

            $data = self::getCandidateLedgerBook($param);
            $pdf_url = route('ew.candidateLedgerBookPdf').'?candidate='.$candidate_id;

            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('ew.reports.candidateLedgerBook.report', $data);
        }


        //Candidate Ledger Book Data PDF
        public function candidateLedgerBookPdf(Request $request) {
            $param['candidate_id'] = $request->candidate;

            $data = self::getCandidateLedgerBook($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('ew.reports.candidateLedgerBook.report', $data);
            $file_name = 'candidate-ledger-book-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Candidate Ledger Book Data
        public static function getCandidateLedgerBook($param) {
            $candidate_id = $data['candidate_id'] = $param['candidate_id'];

            $data['candidateDetails'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name', 'ew_references.reference_name')
                ->where('ew_candidates.valid', 1)
                ->where('ew_candidates.id', $candidate_id)
                ->first();

            $data['receivable'] = EwCandidateTransaction::valid()
                ->select(DB::raw('(sum(receivable_amount)-sum(less_amount)) as receivable_amount'))
                ->where('candidate_id', $candidate_id)
                ->where(function($query){
                    $query->where('transaction_status', 1)
                        ->orWhere('transaction_status', 5);
                })
                ->first();

            $data['candidateTransactionHis'] = EwCandidateTransaction::join('ew_collectable_account_heads', 'ew_candidate_transaction.collectable_account', '=', 'ew_collectable_account_heads.id')
                ->select('ew_candidate_transaction.*', 'ew_collectable_account_heads.account_head')
                ->where('ew_candidate_transaction.valid', 1)
                ->where('ew_candidate_transaction.transaction_status', '!=', 1)
                ->where('ew_candidate_transaction.transaction_status', '!=', 5)
                ->where('ew_candidate_transaction.candidate_id', $candidate_id)
                ->get();

            return $data;
        }

    //-----------AMOUNT STATUS REPORT----------
        //Amount Status Report
        public function amountStatusReport(Request $request)
        {
            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()->get();

            $candidates = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                ->select('ew_candidates.id', 'ew_candidates.ew_project_id', DB::raw("concat(ew_candidates.candidate_id, ' - ', ew_candidates.candidate_name) as candidate_id_name"), 'ew_projects.project_name')
                ->where('ew_candidates.valid', 1)->get()->keyBy('id')->all();
            $data['candidates'] = str_replace("\\", "\\\\", json_encode($candidates, JSON_HEX_APOS | JSON_HEX_QUOT));

            return view('ew.reports.amountStatusReport.index', $data);
        }

        //Amount Status Report Data
        public function amountStatusReportData(Request $request) {
            $param = $request->all();

            $data = self::getAmountStatusReport($param);
            $pdf_url = route('ew.amountStatusReportDataPdf', $param);
            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('ew.reports.amountStatusReport.report', $data);
        }

        //Amount Status Report Data PDF
        public function amountStatusReportDataPdf(Request $request) {
            $param = $request->all();

            $data = self::getAmountStatusReport($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('ew.reports.amountStatusReport.report', $data);
            $file_name = 'amount-status-report-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Amount Status Report
        public static function getAmountStatusReport($param) {

            $projectId = $param['projectId'];
            $candidate_id = $param['candidate'];
            $candidateBox = @$param['candidateBox'];

            $candidate_id = ($candidateBox) ? explode(',', $candidateBox) : explode(',', $candidate_id);
            $candidateNumber = count($candidate_id);

            $data['candidate'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name', 'ew_references.reference_name')
                ->where(function($query) use($candidate_id, $candidateNumber, $projectId, $candidateBox){
                    if ($candidate_id[0]) {
                        if (count($candidate_id)==1) {
                            $query->where('ew_candidates.id', $candidate_id[0]);
                        } else {
                            $query->whereIn('ew_candidates.id', $candidate_id);
                        }
                    }
                    if (empty($candidateBox) && $projectId) {
                        $query->where('ew_candidates.ew_project_id', $projectId);
                    }
                })
                ->where('ew_candidates.valid', 1)
                ->get();

            $data['receivable'] = EwCandidateTransaction::valid()
                ->select('candidate_id', DB::raw('(sum(receivable_amount)-sum(less_amount)) as receivable_amount'))
                ->where(function($query){
                    $query->where('transaction_status', 1)
                        ->orWhere('transaction_status', 5);
                })
                ->where('account_transaction_no', 0)
                ->groupBy('candidate_id')
                ->get()->keyBy('candidate_id')->all();

            $data['candidateTransaction'] = EwCandidateTransaction::join('ew_candidates', 'ew_candidate_transaction.candidate_id', '=', 'ew_candidates.id')
                ->join('ew_collectable_account_heads', 'ew_candidate_transaction.collectable_account', '=', 'ew_collectable_account_heads.id')
                ->select('ew_candidate_transaction.*', 'ew_collectable_account_heads.account_head')
                ->where(function($query) use($candidate_id, $candidateNumber, $projectId, $candidateBox){
                    if ($candidate_id[0]) {
                        if (count($candidate_id)==1) {
                            $query->where('ew_candidate_transaction.candidate_id', $candidate_id[0]);
                        } else {
                            $query->whereIn('ew_candidate_transaction.candidate_id', $candidate_id);
                        }
                    }
                    if (empty($candidateBox) && $projectId) {
                        $query->where('ew_candidates.ew_project_id', $projectId);
                    }
                })
                ->where('ew_candidate_transaction.account_transaction_no', '!=', '0')
                ->where('ew_candidate_transaction.valid', 1)
                ->orderBy('transaction_date', 'asc')
                ->get()->groupBy('candidate_id')->all();

            return $data;
        }

    //-----------AMOUNT LESS REPORT----------
        //Amount Less Report
        public function amountLessReport(Request $request)
        {
            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()->get();
            $data['ewReferences'] = EwReferences::valid()->get();

            return view('ew.reports.amountLessReport.index', $data);
        }

        //Amount Less Report Data
        public function amountLessReportData(Request $request) {
            $param = $request->all();
            $data = self::getAmountLessReport($param);
            $pdf_url = route('ew.amountLessReportDataPdf', $param);
            $data = array_merge($data, ['pdf_url' => $pdf_url]);
            return view('ew.reports.amountLessReport.report', $data);
        }


        //Amount Less Report Data PDF
        public function amountLessReportDataPdf(Request $request) {
            $param = $request->all();
            $data = self::getAmountLessReport($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('ew.reports.amountLessReport.report', $data);
            $file_name = 'amount-less-report-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Amount Less Report
        public static function getAmountLessReport($param) {
            $projectId = $data['projectId'] = $param['projectId'];
            $referenceId = $data['referenceId'] = $param['referenceId'];
            $candidate_id = $data['candidate_id'] = $param['candidate'];

            $data['candidate'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name', 'ew_references.reference_name')
                ->where(function($query) use($candidate_id, $projectId, $referenceId){
                    if ($candidate_id) {
                        $query->where('ew_candidates.id', $candidate_id);
                    }
                    if ($projectId) {
                        $query->where('ew_candidates.ew_project_id', $projectId);
                    }
                    if ($referenceId) {
                        $query->where('ew_candidates.reference_id', $referenceId);
                    }
                })
                ->where('ew_candidates.valid', 1)
                ->get()->keyBy('id')->all();

            $data['candidateTransaction'] = EwCandidateTransaction::join('ew_candidates', 'ew_candidate_transaction.candidate_id', '=', 'ew_candidates.id')
                ->join('ew_collectable_account_heads', 'ew_candidate_transaction.collectable_account', '=', 'ew_collectable_account_heads.id')
                ->select('ew_candidate_transaction.*', 'ew_collectable_account_heads.account_head')
                ->where(function($query) use($candidate_id, $projectId, $referenceId){
                    if ($candidate_id) {
                        $query->where('ew_candidate_transaction.candidate_id', $candidate_id);
                    }
                    if ($projectId) {
                        $query->where('ew_candidates.ew_project_id', $projectId);
                    }
                    if ($referenceId) {
                        $query->where('ew_candidates.reference_id', $referenceId);
                    }
                })
                ->where('ew_candidate_transaction.transaction_status', 5)
                ->where('ew_candidate_transaction.valid', 1)
                ->orderBy('transaction_date', 'asc')
                ->get()->groupBy('candidate_id')->all();

            return $data;
        }


      //-----------REFERENCE LEDGER REPORT----------
        //Reference Ledger Report
        public function referenceLedgerReport(Request $request)
        {
            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()->get();
            $data['ewReferences'] = EwReferences::valid()->get();

            return view('ew.reports.referenceLedgerReport.index', $data);
        }

        //Reference Ledger Report Data
        public function referenceLedgerReportData(Request $request) {
            $projectId = $param['projectId'] = $request->projectId;
            $referenceId = $param['referenceId'] = $request->referenceId;

            $data = self::getReferenceLedgerReport($param);
            $pdf_url = route('ew.referenceLedgerReportDataPdf').'?projectId='.$projectId.'&referenceId='.$referenceId;

            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('ew.reports.referenceLedgerReport.report', $data);
        }

        //Reference Ledger Report Data PDF
        public function referenceLedgerReportDataPdf(Request $request) {
        $param['projectId'] = $request->projectId;
        $param['referenceId'] = $request->referenceId;

        $data = self::getReferenceLedgerReport($param);
        $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
        $pdf = PDF::loadView('ew.reports.referenceLedgerReport.report', $data);
        $file_name = 'reference-ledger-report-'.date('Y-m-d').'.pdf';
        return $pdf->stream($file_name);
        }

        //Get Reference Ledger Report Data
        public static function getReferenceLedgerReport($param) {
            $projectId = $data['projectId'] = $param['projectId'];
            $referenceId = $data['referenceId'] = $param['referenceId'];

            $data['reference'] = EwReferences::valid()->find($referenceId);
            $data['candidate'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
                ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name')
                ->where(function($query) use($projectId){
                    if ($projectId) {
                        $query->where('ew_candidates.ew_project_id', $projectId);
                    }
                })
                ->where('ew_candidates.reference_id', $referenceId)
                ->where('ew_candidates.valid', 1)
                ->get();

            $data['candidateTransaction'] = EwCandidateTransaction::join('ew_candidates', 'ew_candidate_transaction.candidate_id', '=', 'ew_candidates.id')
                    ->join('ew_collectable_account_heads', 'ew_candidate_transaction.collectable_account', '=', 'ew_collectable_account_heads.id')
                    ->select('ew_candidate_transaction.*', 'ew_collectable_account_heads.account_head')
                    ->where(function($query) use($projectId){
                        if ($projectId) {
                            $query->where('ew_candidates.ew_project_id', $projectId);
                        }
                    })
                    ->where('ew_candidates.reference_id', $referenceId)
                    ->where('ew_candidate_transaction.transaction_status', '!=', 1)
                    ->where('ew_candidate_transaction.transaction_status', '!=', 5)
                    ->where('ew_candidate_transaction.valid', 1)
                    ->orderBy('transaction_date', 'asc')
                    ->get()->groupBy('candidate_id')->all();
            return $data;
        }


        //-----------REFERENCE LIST REPORT----------
        //Reference List Report
        public function referenceReport(Request $request)
        {
            $data['inputData'] = $request->all();

            return view('ew.reports.referenceReport.index', $data);
        }

        //Reference List Report Data
        public function referenceReportData(Request $request)
        {
            $data['inputData'] = $request->all();
            $ascDesc = Helper::ascDesc($data, ['']);
            $paginate = Helper::paginate($data);
            $data['sn'] = $paginate->serial;

            $data['references'] = EwReferences::valid()->get();

            return view('ew.reports.referenceReport.report', $data);
        }


        //Reference List Report PDF
        public function referenceReportPdf(Request $request) {
            $data['inputData'] = $request->all();
            $ascDesc = Helper::ascDesc($data, ['']);
            $paginate = Helper::paginate($data);
            $data['sn'] = $paginate->serial;

            $data['references'] = EwReferences::valid()->get();

            $pdf = PDF::loadView('ew.reports.referenceReport.referenceReportPdf', $data);
            $file_name = 'reference-report-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Amount Status Report
        public static function getReferenceReport($param) {
            $data['references'] = EwReferences::valid()->get();
            return $data;
        }

      //-----------Candidate Advance REPORT----------
        //Candidate Advance Report
        public function candidateAdvance(Request $request)
        {
            $data['inputData'] = $request->all();
            $data['ewProjects'] = EwProjects::valid()->get();
            $data['ewReferences'] = EwReferences::valid()->get();

            return view('ew.reports.candidateAdvance.index', $data);
        }

        //Candidate Advance Data
        public function candidateAdvanceData(Request $request) {
            $projectId = $param['projectId'] = $request->projectId;
            $referenceId = $param['referenceId'] = $request->referenceId;

            $data = self::getCandidateAdvance($param);

            $pdf_url = route('ew.candidateAdvanceDataPdf').'?projectId='.$projectId.'&referenceId='.$referenceId;

            $data = array_merge($data, ['pdf_url' => $pdf_url]);

            return view('ew.reports.candidateAdvance.report', $data);
        }

        //Candidate Advance PDF
        public function candidateAdvanceDataPdf(Request $request) {
            $param['projectId'] = $request->projectId;
            $param['referenceId'] = $request->referenceId;

            $data = self::getCandidateAdvance($param);
            $data = array_merge($data, ['pdf' => true, 'pdf_url'=>Null]);
            $pdf = PDF::loadView('ew.reports.candidateAdvance.report', $data);
            $file_name = 'candidate-advance-report-'.date('Y-m-d').'.pdf';
            return $pdf->stream($file_name);
        }

        //Get Candidate Advance Data
        public static function getCandidateAdvance($param) {
            $projectId = $data['projectId'] = $param['projectId'];
            $referenceId = $data['referenceId'] = $param['referenceId'];

            $data['candidates'] = EwCandidateTransaction::join('ew_candidates', 'ew_candidate_transaction.candidate_id', '=', 'ew_candidates.id')
                    ->join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
                    ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                    ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_references.reference_name', DB::raw('(SUM(ew_candidate_transaction.received_amount)-SUM(ew_candidate_transaction.paid_amount)) as advBalance'))
                    ->where(function($query) use($projectId, $referenceId){
                        if ($projectId) {
                            $query->where('ew_candidates.ew_project_id', $projectId);
                        }
                        if ($referenceId) {
                            $query->where('ew_candidates.reference_id', $referenceId);
                        }
                    })
                    ->where('ew_candidates.flight_status', 0)
                    ->where('ew_candidate_transaction.valid', 1)
                    ->groupBy('ew_candidates.id')
                    ->orderBy('candidate_id', 'asc')
                    ->get();
            return $data;
        }
}
