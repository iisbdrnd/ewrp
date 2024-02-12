<?php

namespace App\Http\Controllers\Recruitment;
use DB;
use Auth;
use Helper;
use Validator;
use Carbon\Carbon;
use App\Http\Requests;
use App\Model\EwTrades;
use App\Model\EwAviation;
use App\Model\EwInterview;
use App\Model\EwReferences;
use Illuminate\Http\Request;
use App\Model\EwCandidatesCV;
use App\Model\EwPassportForm;
use App\Model\EwInterviewCall;
use App\Http\Controllers\Controller;

class PassportFormController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $data['inputData'] = $request->all();
    return view('recruitment.passportForm.list', $data);
  }

  public function passportData(Request $request) 
  {
    $data           = $request->all();
    $search         = $request->search;
    $data['access'] = Helper::userPageAccess($request);
    $ascDesc        = Helper::ascDesc($data, ['id', 'full_name', 'passport_no', 'reference_name', 'dealer']);
    $paginate       = Helper::paginate($data);
    $data['sn']     = $paginate->serial;

    $data['passportForms'] = $cv =  EwPassportForm::valid()
    ->where(function($query) use ($search)
    {
      $query->where('emergency_contact', 'LIKE', '%'.$search.'%')
      ->orWhere('father_name', 'LIKE', '%'.$search.'%')
      ->orWhere('full_name', 'LIKE', '%'.$search.'%')
      ->orWhere('national_id', 'LIKE', '%'.$search.'%')
      ->orWhere('passport_no', 'LIKE', '%'.$search.'%')
      ->orWhere('spouse_name', 'LIKE', '%'.$search.'%')
      ->orWhere('surname', 'LIKE', '%'.$search.'%')
      ->orWhere('telephone_no', 'LIKE', '%'.$search.'%');
      })
    ->orderBy($ascDesc[0], $ascDesc[1])
    ->paginate($paginate->perPage);

    $data['totalCV'] = EwCandidatesCV::valid()->count();

    return view('recruitment.passportForm.listData', $data);

    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request)
    {
      $data['references'] = EwReferences::valid()->get();
      $data['inputData']  = $request->all();
      $data['countries']  = DB::table('countries')->where('id', 1)->get();
      $data['years'] = DB::table('ew_years')->get();
      $data['trades'] = EwTrades::valid()->get();
      $data['expCountries'] = DB::table('countries')->where('id','!=',1)->get();
      return view('recruitment.passportForm.create', $data);
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
      // return $request->all();
        $output       = array();
        $input        = $request->all();
        $validator    = [
        // 'passport_no' => 'required|unique:ew_candidatescv',
        // 'national_id' => 'required|unique:ew_candidatescv'
        ];

        $validator = Validator::make($input, $validator);
        $cv_number = Carbon::now()->format('y').Carbon::now()->format('m').Carbon::now()->format('d').'-';

        if ($validator->passes()) {

        $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
        $date_of_issue         = date("Y-m-d", strtotime($request->date_of_issue));
        $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));

        $passportArrData = [
          'age'                   => $request->age,
          'country_code'          => $request->country_code,
          'date_of_birth'         => $date_of_birth,
          'date_of_issue'         => $date_of_issue,
          'emergency_contact'     => $request->emergency_contact,
          'father_name'           => $request->father_name,
          'full_name'             => $request->full_name,
          'gender'                => $request->gender,
          'issue_of_authority'    => $request->issue_of_authority,
          'mother_name'           => $request->mother_name,
          'national_id'           => $request->national_id,
          'nationality'           => $request->nationality,
          'passport_expired_date' => $passport_expired_date,
          'passport_no'           => $request->passport_no,
          'passport_type'         => $request->passport_type,
          'permanent_address'     => $request->permanent_address,
          'place_of_birth'        => $request->place_of_birth,
          'spouse_name'           => $request->spouse_name,
          'surname'               => $request->surname,
          'telephone_no'          => $request->telephone_no,
        ];

        $table = EwPassportForm ::create($passportArrData);

        $output['messege'] = 'Passport form has been created';
        $output['msgType'] = 'success';

      }else{

        $output = Helper::vError($validator);

      }

    echo json_encode($output);

  }

    public function cvPrintPreview(Request $request)
    {
        $data['candidate_id']  = $request->candidate_id;
        $data['cvPrints']      = EwCandidatesCV::valid()->where('id', $request->candidate_id)->first();
        $tradeIds              = EwInterview::valid()->where('ew_candidatescv_id', $request->candidate_id)->first();
        $tradeSelected         = $tradeIds->selected_trade;
        $tradeApplied          = $tradeIds->trade_applied;
        $pdf_url               = url('recruitment/cv-print-preview?candidate_id='.$request->candidate_id);
        if($tradeSelected      === null){
        $data['tradeSelected'] = null;
        }else{
        $data['tradeSelected'] = $tradeSelected;
        }
        if($tradeApplied       === null){
        $data['tradeApplied']  = null;
        }else{
        $data['tradeApplied']  = $tradeApplied;
        }
        $data = array_merge($data, ['pdf_url' => $pdf_url]);
        return view('recruitment.passportForm.cvPrintPreview', $data);
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
  public function edit($id){
    $data['references']        = EwReferences::valid()->get();
    $data['editpassportForms'] = $candidates = EwPassportForm:: valid()->where('id', $id)->first();
    $data['singgleRefs']       = EwReferences::valid()->where('id', $candidates->reference_id)->first();
    $data['tradeIds']          = json_decode($candidates->trade, true);
    $data['countries']         = DB          ::table('countries')->get();
    $data['years']             = DB          ::table('ew_years')->get();
    $data['trades']            = EwTrades    ::valid()->get();
    $data['expCountries']      = DB          ::table('countries')->where('id','! = ',1)->get();
    return view('recruitment.passportForm.update', $data);
  }

  /**
   * Update candidate cv.
   *
   * @param  Request  $request
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {

// return $request->date_of_birth;
  $date_of_birth         = date("Y-m-d", strtotime($request->date_of_birth));
  $date_of_issue         = date("Y-m-d", strtotime($request->date_of_issue));
  $passport_expired_date = date("Y-m-d", strtotime($request->passport_expired_date));

 $data = [
          'age'                   => $request->age,
          'country_code'          => $request->country_code,
          'date_of_birth'         => $date_of_birth,
          'date_of_issue'         => $date_of_issue,
          'emergency_contact'     => $request->emergency_contact,
          'father_name'           => $request->father_name,
          'full_name'             => $request->full_name,
          'gender'                => $request->gender,
          'issue_of_authority'    => $request->issue_of_authority,
          'mother_name'           => $request->mother_name,
          'national_id'           => $request->national_id,
          'nationality'           => $request->nationality,
          'passport_expired_date' => $passport_expired_date,
          'passport_no'           => $request->passport_no,
          'passport_type'         => $request->passport_type,
          'permanent_address'     => $request->permanent_address,
          'place_of_birth'        => $request->place_of_birth,
          'spouse_name'           => $request->spouse_name,
          'surname'               => $request->surname,
          'telephone_no'          => $request->telephone_no,
        ];

  EwPassportForm::valid()->find($id)->update($data);
  $output['messege'] = 'Passport Form has been updated';
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
    // $EwAviationBill = EwCandidatesCV::where('id', $id)->first();
    // if (!empty($EwAviationBill)) {
    //    echo "This Aviation is used. You can not delete this!!!";
    // }
    // else{
    //     EwAviation::valid()->find($id)->delete();
    // }
    EwCandidatesCV::valid()->find($id)->delete();
  }
}
