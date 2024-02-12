<?php

namespace App\Http\Controllers\Recruitment;

use DB;
use Auth;
use Helper;
use Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Model\EwMobilization;
use App\Model\EwProjectTrades;
use App\Http\Controllers\Controller;
use App\Model\EwProjectCollectableSelection;

class MobilizationList extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('recruitment.mobilizationList.list', $data);
    }

    public function mobilizationListData(Request $request) {
        $data           = $request->all();
        $search         = $request->search;
        
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['name', 'updated_at']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $data['ewMobilizations'] = EwMobilization::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('custom_sl')
            ->paginate($paginate->perPage);

        return view('recruitment.mobilizationList.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('recruitment.mobilizationList.create', $data);
    }

    public function add()
    {
        
        return view('recruitment.mobilizationList.add');
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
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            $mob_id = EwMobilization::create($input);

            EwMobilization::valid()->find($mob_id->id)->update([
                "custom_sl" => $mob_id->id
            ]);

            $output['messege'] = 'Mobilization list has been created';
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
        $data['ewMobilization'] = EwMobilization::valid()->find($id);
        return view('recruitment.mobilizationList.update', $data);
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
            'name' => 'required'
        ]);

        if ($validator->passes()) {
        EwMobilization::valid()->find($id)->update($input);
        $output['messege'] = 'Mobilization list has been updated';
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
        EwMobilization::valid()->find($id)->delete();
    }


    /**
     * @param  int  $id
     * @return Response
     */
    public function getMobilizationByProject(Request $request)
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

            return view('recruitment.collectableSelection.projectCollectableSelections', $data);
    }

    /*generate custom moblization list*/

    public function generateMobilizeList(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['mobilizations']  = EwMobilization::valid()->orderBy('custom_sl')->get(); 
        return view('recruitment.mobilizationList.sortList', $data);
    }

    public function generateMobilizeListAction(Request $request)
    {
        DB::beginTransaction();
        $data['inputData'] = $request->all();

        $mobilize_id = $request->mobilization_id;

        foreach ($mobilize_id as $key => $value) {
            EwMobilization::valid()->find($value)->update([
                "custom_sl" => $key+1
            ]);
        }
        $output['messege'] = 'Mobilization list has been updated';
        $output['msgType'] = 'success';

        echo json_encode($output);
        DB::commit();


    }
}
