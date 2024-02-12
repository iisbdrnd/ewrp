<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwFlights;
use App\Model\EwCandidates;
use App\Model\EwProjects;
use App\Model\EwCandidateTransaction;
use App\Model\EwAccountTransaction;
use App\Model\EwAccountConfiguration;
use App\Model\EwTrades;
use App\Model\EwReferences;

class FlightEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();

        return view('ew.flightEntry.list', $data);
    }

    public function flightEntryListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['candidate_name', 'voucher_no', 'flight_no', 'flight_date']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['ewFlights'] = EwFlights::join('ew_candidates', 'ew_flights.candidate_id', '=', 'ew_candidates.id')
            ->select('ew_flights.*', 'ew_candidates.candidate_name')
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidates.candidate_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_flights.flight_no', 'LIKE', '%'.$search.'%');
            })
            ->where('ew_flights.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.flightEntry.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['ewProjects'] = EwProjects::valid()->get();
        $data['ewCandidates'] = EwCandidates::valid()->get();

        return view('ew.flightEntry.create', $data);
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
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'ew_project_id'     => 'required',
            'candidate_id'      => 'required',
            'flight_no'         => 'required',
            'flight_date'       => 'required'
        ]);

        if ($validator->passes()) {
            $candidate = EwCandidates::Valid()->find($request->candidate_id);
            if(!empty($candidate)) {
                //Candidate's due balance check
                $candidateInfo = Helper::getCandidateBalance($request->candidate_id);
                if ($candidateInfo->balance==0) {
                    $previousFlight = EwFlights::valid()->where('candidate_id', $request->candidate_id)->where('flight_status', 1)->first();
                    //For Previous flight cencel
                    if (!empty($previousFlight)) {
                        $previousFlight->update([
                            'flight_status'          => 0 //Flight status update, 0=Cancel
                        ]);
                    }

                    $flightDate = DateTime::createFromFormat('d/m/Y', $request->flight_date);
                    $flightDate = $flightDate->format('Y-m-d');
                    //$flightDate = $request->flight_date;
                    $input['flight_date'] = $flightDate;
                    $input['flight_status'] = 1; // 1 = Active Flight
                    //Candidate's flight create
                    EwFlights::create($input);

                    if($candidate->flight_status==0) {
                        //Account Transaction
                        $candidate_liability = EwAccountConfiguration::valid()->where('particular', 'candidate_liability')->first()->account_code;
                        $candidate_income = EwAccountConfiguration::valid()->where('particular', 'candidate_income')->first()->account_code;
                        $transaction_status = 5;
                        $transaction = Helper::getTransactionInstrumentNo($transaction_status);
                        $account_input = [
                            'transaction_no'        => $transaction['transaction_no'],
                            'instrument_no'         => $transaction['instrument_no'],
                            'voucher_type'          => 'JV',
                            'transaction_date'      => date('Y-m-d'),
                            'remarks'               => $request->remarks,
                            'ew_project_id'         => $candidate->ew_project_id,
                            'candidate_id'          => $request->candidate_id,
                            'transaction_status'    => $transaction_status
                        ];

                        $candidateCurLiability = EwAccountTransaction::valid()
                                ->select('*', DB::raw('SUM(credit_amount) as totalCredit, SUM(debit_amount) as totalDebit'))
                                ->where('account_code', $candidate_liability)
                                ->where('candidate_id', $request->candidate_id)
                                ->groupBy('collectable_account')
                                ->get();

                        foreach($candidateCurLiability as $candidateCurLiability) {
                            $amount = $candidateCurLiability->totalCredit-$candidateCurLiability->totalDebit;
                            $account_input["collectable_account"] = $candidateCurLiability->collectable_account;
                            //Debit
                            $account_input["account_code"] = $candidate_liability;
                            $account_input["contra_account_code"] = $candidate_income;
                            $account_input["debit_amount"] = $amount;
                            $account_input["credit_amount"] = 0;
                            EwAccountTransaction::create($account_input);
                            //Credit
                            $account_input["account_code"] = $candidate_income;
                            $account_input["contra_account_code"] = $candidate_liability;
                            $account_input["debit_amount"] = 0;
                            $account_input["credit_amount"] = $amount;
                            EwAccountTransaction::create($account_input);
                        }

                        //Flight Info. Update in Candidate Table
                        EwCandidates::valid()->find($request->candidate_id)->update([
                            'candidate_flight_date'     => $flightDate,
                            'flight_status'             => 1, //Flight status update, 1=Yes
                            'flight_transaction_no'     => $transaction['transaction_no'],
                            'flight_transaction_date'   => date('Y-m-d')
                        ]);
                    }else{
                        //Flight Date Update in Candidate Table
                        EwCandidates::valid()->find($request->candidate_id)->update([
                            'candidate_flight_date'          => $flightDate
                        ]);
                    }

                    //Success message
                    $output['messege'] = 'Flight Entry has been created';
                    $output['msgType'] = 'success';
                } else {
                    //Alert message for candidate has to pay the money
                    $output['messege'] = 'Sorry you have pay to money';
                    $output['msgType'] = 'danger';
                }
            } else {
                $output['messege'] = 'Candidate is not exist';
                $output['msgType'] = 'danger';
            }
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
        DB::commit();
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
        $data['ewProjects'] = EwProjects::valid()->get();
        $data['ewCandidates'] = EwCandidates::valid()->get();

        //Candidate Flight Information
        $data['ewFlights'] = $candidateFlightInfo = EwFlights::valid()->find($id);
        //Candidate General Information
        $data['candidateInfo'] = $candidateInfo = EwCandidates::valid()->where('ew_project_id', $candidateFlightInfo->ew_project_id)->find($candidateFlightInfo->candidate_id);
        //Candidate Project Information
        $data['candidateProject'] = EwProjects::valid()->find($candidateFlightInfo->ew_project_id);
        //Candidate Trade Information
        $data['candidateTrade'] = EwTrades::valid()->find($candidateInfo->trade_id);
        //Candidate Reference Information
        $data['candidateReference'] = EwReferences::valid()->find($candidateInfo->reference_id);


        return view('ew.flightEntry.update', $data);
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
        $output = array();
        $input = $request->all();
        $project_id = Auth::user()->get()->project_id;

        $validator = Validator::make($input, [
            'flight_no'         => 'required',
            'flight_date'       => 'required'
        ]);

        if ($validator->passes()) {
            $flightDate = DateTime::createFromFormat('d/m/Y', $request->flight_date);
            $input['flight_date'] = $flightDate->format('Y-m-d');
            EwFlights::valid()->find($id)->update($input);

            //For Flight Date Update in Candidate Table
            $flight_candidate_id = EwFlights::valid()->find($id)->candidate_id;
            EwCandidates::valid()->find($flight_candidate_id)->update([
                'candidate_flight_date'          => $flightDate
            ]);

            $output['messege'] = 'Flight Entry has been updated';
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
        EwFlights::valid()->find($id)->delete();
    }
}
