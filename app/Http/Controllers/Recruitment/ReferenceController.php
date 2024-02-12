<?php

namespace App\Http\Controllers\Recruitment;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwReferences;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('recruitment.reference.list', $data);
    }

    public function referenceListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['reference_name', 'reference_phone', 'reference_email', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['ewReferences'] = EwReferences::valid()
            ->where(function($query) use ($search)
            {
                $query->where('reference_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('reference_phone', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.reference.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['dealers'] = DB::table('users')->where('valid', 1)->get();
        
        return view('recruitment.reference.create', $data);
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
        $output = array();
        $input = $request->all();
        //$dealer = json_encode($request->dealer);
        $dealer = $request->dealer;
        
        $validator = Validator::make($input, [
            'reference_name' => 'required',
            'reference_phone' => 'required',
            'dealer' => 'required'
        ]);

        // $arr = [];
        // if($request->dealer!=null){

        //     foreach($request->dealer as $dealerId){
        //         $arr[$dealerId] = $dealerId;
        //     }
        // }

        if ($validator->passes()) {
            EwReferences:: create([
                'reference_name'    => $request->reference_name,
                'reference_phone'   => $request->reference_phone,
                'reference_email'   => $request->reference_email,
                //'dealer'            => json_encode($arr),
                'dealer'            => $dealer,
                'reference_address' => $request->reference_address,
            ]);

            $output['messege'] = 'Reference has been created';
            $output['msgType'] = 'success';
            } else {
            $output = Helper::vError($validator);
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
        $data['ewReferences'] = $dealerArray =  EwReferences::valid()->find($id);
        $data['dealers'] = DB::table('users')->where('valid', 1)->get();
        $data['dealerArrId'] = json_decode($dealerArray->dealer, true);
        return view('recruitment.reference.update', $data);
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
            'reference_name'    => 'required',
            'reference_phone'   => 'required',
            'dealer'            => 'required'
        ]);
        // $dealer = $request->dealer==null?0:json_encode($request->dealer);    
        // $arr = [];
        // if($request->dealer!=null){

        //     foreach($request->dealer as $dealerId){
        //         $arr[$dealerId] = $dealerId;
        //     }
        // }

        if ($validator->passes()) {
            
            EwReferences::where('id', $id)->where('valid', 1)->update([
                'reference_name'    => $request->reference_name,
                'reference_phone'   => $request->reference_phone,
                'reference_email'   => $request->reference_email,
                //'dealer'            => json_encode($arr),
                'dealer'            => $request->dealer,
                'reference_address' => $request->reference_address,
            ]);
        
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
