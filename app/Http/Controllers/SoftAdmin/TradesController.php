<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwTrades;
use App\Model\EwProjectTrades;
use App\Model\EwProjectCollectableSelection;

class TradesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('softAdmin.trades.list', $data);
    }

    public function tradesListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['trade_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['ewTrades'] = EwTrades::valid()
            ->where(function($query) use ($search)
            {
                $query->where('trade_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.trades.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('softAdmin.trades.create', $data);
    }

    public function add()
    {
        
        return view('softAdmin.trades.add');
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
            'trade_name' => 'required'
        ]);

        if ($validator->passes()) {
            EwTrades::create($input);

            //For In Page Add
            $trade = EwTrades::valid()->orderBy('id', 'desc')->first();
            $output['value'] = $trade->id;
            $output['text'] = $trade->trade_name;

            $output['messege'] = 'Trade has been created';
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
        $data['ewTrade'] = EwTrades::valid()->find($id);
        return view('softAdmin.trades.update', $data);
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
            'trade_name' => 'required'
        ]);

        if ($validator->passes()) {
        EwTrades::valid()->find($id)->update($input);
        $output['messege'] = 'Trade has been updated';
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
        EwTrades::valid()->find($id)->delete();
    }


    /**
     * @param  int  $id
     * @return Response
     */
    public function getTradesByProject(Request $request)
    {
        $project_id = $request->project_id;
        $project_trades = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
                            ->select('ew_project_trades.*', 'ew_trades.trade_name')
                            ->where('ew_project_trades.ew_project_id', $project_id)
                            ->where('ew_project_trades.valid', 1)
                            ->get();
        $html = '<option value=""></option>';
        foreach ($project_trades as $trade) {
            $html .= '<option value="'.$trade->trade_id.'">'.$trade->trade_name.'</option>';
        }

        return $html;
    }

    public function getProjectCollectableSelection(Request $request)
    {
        $project_id = $request->project_id;
        
        $data['projectCollectableSelections'] = EwProjectCollectableSelection::leftJoin('ew_collectable_account_heads', 'ew_project_collectable_selection.collectable_account_id', '=', 'ew_collectable_account_heads.id')
            ->select('ew_collectable_account_heads.*')
            ->where('ew_project_collectable_selection.ew_project_id', $project_id)
            ->where('ew_project_collectable_selection.valid', 1)
            ->where('ew_collectable_account_heads.valid', 1)
            ->get()
            ->chunk(2);

            return view('softAdmin.collectableSelection.projectCollectableSelections', $data);
    }
}
