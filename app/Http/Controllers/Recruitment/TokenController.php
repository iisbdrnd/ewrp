<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwToken;


class TokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('recruitment.token.list', $data);
    }

    public function tokenInfoListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['token_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['tokens'] = EwToken::valid()
            ->where(function($query) use ($search)
            {
                $query->where('token_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.token.listData', $data);
    }



    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return Response
    //  */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        // $data['ewAccount'] = EwChartOfAccounts::valid()->get();
        // $data = array_merge($data, AccountController::accPayableComboData());
        return view('recruitment.token.create', $data);
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
        // $output = array();
        // $account_code=$request->account_code;
        // $input = $request->all();

        // $validator = Validator::make($request->all(), [
        //     'company_name'     => 'required',
        //     'account_name'     => 'required',
        //     'account_code'     => 'required',
        //     'address'          => 'required',
        //     'contact_person'   => 'required',
        //     'contact_no'       => 'required'

        // ]);

        // if ($validator->passes()) {
        //     // EwAviation::create($input);
        //     EwAviation::create([
        //         'company_name'                 => $request->company_name,
        //         'account_name'                 => $request->account_name,
        //         'account_code'                 => $request->account_code,
        //         'address'                      => $request->address,
        //         'contact_person'               => $request->contact_person,
        //         'contact_no'                   => $request->contact_no,

        //     ]);
        //     $output['messege'] = 'Aviation has been created';
        //     $output['msgType'] = 'success';
        // } else {
        //     $output = Helper::vError($validator);
        // }

        // echo json_encode($output);
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
        // $data['aviationDetails'] = EwAviation::valid()->where('id', $id)->first();
        // $data = array_merge($data, AccountController::accPayableComboData());
        // return view('ew.aviation.update', $data);
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
        // $output = array();
        // $input = $request->all();
        // $project_id = Auth::user()->get()->project_id;

        // $validator = Validator::make($input, [
        //     'company_name'            => 'required',
        //     'account_name'            => 'required',
        //     'account_code'            => 'required',
        //     'address'                 => 'required',
        //     'contact_person'          => 'required',
        //     'contact_no'              => 'required'            
        // ]);
        // if ($validator->passes()) {
        // $data = [
        //     'company_name'            => $request->company_name,
        //     'account_name'            => $request->account_name,
        //     'account_code'            => $request->account_code,
        //     'address'                 => $request->address,
        //     'contact_person'          => $request->contact_person,
        //     'contact_no'              => $request->contact_no,            

        // ];
        // EwAviation::valid()->find($id)->update($data);

        // $output['messege'] = 'Company has been updated';
        // $output['msgType'] = 'success';
        // } else {
        //     $output = Helper::vError($validator);
        // }

        // echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {   
        // $EwAviationBill = EwAviationBill::where('aviation_id', $id)->first();
        // if (!empty($EwAviationBill)) {
        //    echo "This Aviation is used. You can not delete this!!!";
        // }
        // else{
        //     EwAviation::valid()->find($id)->delete();
        // }
        
    }
}
