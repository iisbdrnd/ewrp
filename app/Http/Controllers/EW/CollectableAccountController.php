<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwCollectableAccountHeads;
use App\Model\EwProjectCollectableSelection;
use App\Model\EwCandidateTransaction;

class CollectableAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();

        return view('ew.collectableAccount.list', $data);
    }

    public function collectableAccountListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['account_head', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['ewCollectableAccountHeads'] = EwCollectableAccountHeads::valid()
            ->where(function($query) use ($search)
            {
                $query->where('account_head', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.collectableAccount.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();




        return view('ew.collectableAccount.create', $data);
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

        $validator = Validator::make($input, [
            'account_head' => 'required'
        ]);

        if ($validator->passes()) {
            EwCollectableAccountHeads::create($input);
            $output['messege'] = 'Account Head has been created';
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
        $data['ewCollectableAccountHeads'] = EwCollectableAccountHeads::valid()->find($id);

        return view('ew.collectableAccount.update', $data);
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
            'account_head' => 'required'
        ]);

        if ($validator->passes()) {
        EwCollectableAccountHeads::valid()->find($id)->update($input);
        $output['messege'] = 'Account Head has been updated';
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
        EwCollectableAccountHeads::valid()->find($id)->delete();
    }


    //CANDIDATE WISE COLLECTABLE HEADS
    public function candidateCollectableAccountHeads(Request $request)
    {
        $data['inputData'] = $request->all();
        $candidateId = $request->candidateId;

        $headId = EwCandidateTransaction::valid()->select('collectable_account')->where('candidate_id', $candidateId)->where('transaction_status', 1)->get();
        $headId = count($headId)>0 ? $headId->toArray() : [];

        $data['collectableAccountHeads'] = EwCollectableAccountHeads::valid()
            ->whereIn('id', $headId)
            ->orderBy('account_head', 'asc')->get();

        return view('ew.collectableAccount.candidateCollectableAccountHeads', $data);
    }

}
