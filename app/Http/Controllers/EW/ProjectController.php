<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwProjects;
use App\Model\EwTrades;
use App\Model\EwProjectTrades;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('ew.project.list', $data);
    }

    public function projectListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['project_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $projects = EwProjects::valid()
            ->where(function($query) use ($search)
            {
                $query->where('project_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        foreach($projects as $project) {
            $project->trades = EwProjectTrades::join('ew_trades', 'ew_project_trades.trade_id', '=', 'ew_trades.id')
                ->select('ew_project_trades.*', 'ew_trades.trade_name')
                ->where('ew_project_trades.ew_project_id', $project->id)
                ->where('ew_project_trades.valid', 1)
                ->get()
                ->implode('trade_name', ', ');
        }

        $data['ewProjects'] = $projects;

        return view('ew.project.listData', $data);
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

        return view('ew.project.create', $data);
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
        $input =  $request->all();
        $project_name = $request->project_name;
        $input_trades = $request->trade_id;
        
        $validator = Validator::make($input, [
            'project_name' => 'required'
        ]);

        if ($validator->passes()) {
            EwProjects::create(['project_name'=>$project_name]);
            $project_id = EwProjects::valid()->orderBy('id', 'desc')->first()->id;

            foreach($input_trades as $trade_id) {
                EwProjectTrades::create(['ew_project_id'=>$project_id, 'trade_id'=>$trade_id]);
            }

            $output['messege'] = 'Project has been created';
            $output['msgType'] = 'success'; 
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
        $data['project'] = EwProjects::valid()->find($id);
        $data['tradeAddAccess'] = Helper::userAccess('trades.create', 'ew');
        $data['selected_trades'] = EwProjectTrades::valid()->select('trade_id')->where('ew_project_id', $id)->get()->pluck('trade_id')->all();
        $data['trades'] = EwTrades::valid()->get();

        return view('ew.project.update', $data);
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

        $output = array();
        $input =  $request->all();
        $project_name = $request->project_name;
        $input_trades = $request->trade_id;
        
        $validator = Validator::make($input, [
            'project_name' => 'required'
        ]);

        if ($validator->passes()) {
            EwProjects::find($id)->update(['project_name'=>$project_name]);

            $selected_trades = collect(EwProjectTrades::valid()->where('ew_project_id', $id)->get()->pluck('trade_id')->all());
            $trade_diff = $selected_trades->diff($input_trades);

            if(!empty($trade_diff)) {
                //remove trade_id
                $project_selected_trades = EwProjectTrades::valid()->whereIn('trade_id', $trade_diff)->where('ew_project_id', $id)->get();
                foreach($project_selected_trades as $project_selected_trade) {
                    EwProjectTrades::valid()->find($project_selected_trade->id)->delete();
                }
            }
            if(!empty($input_trades)) {
                foreach($input_trades as $trade_id) {
                    if(!$selected_trades->contains($trade_id)) {
                        EwProjectTrades::create(['ew_project_id'=>$id, 'trade_id'=>$trade_id]);
                    }
                }
            }

            $output['messege'] = 'Project has been updated';
            $output['msgType'] = 'success'; 
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);

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
}
