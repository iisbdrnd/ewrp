<?php

namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\EwMobilization;
use App\Model\EwProjectCollectableSelection;
use App\Model\EwProjectTrades;
use App\Model\EwVisaJobCategory;
use Auth;
use DB;
use Helper;
use Illuminate\Http\Request;
use Validator;

class VisaJobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('recruitment.visaJobCategory.list', $data);
    }

    public function visaJobCategoryListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['job_category_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['jobCategories'] = EwVisaJobCategory::valid()
            ->where(function($query) use ($search)
            {
                $query->where('job_category_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('recruitment.visaJobCategory.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('recruitment.visaJobCategory.create', $data);
    }

    public function add()
    {
        
        return view('recruitment.visaJobCategory.add');
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
            'job_category_name' => 'required'
        ]);

        if ($validator->passes()) {
            EwVisaJobCategory::create($input);

            //For In Page Add
            // $trade = EwVisaJobCategory::valid()->orderBy('id', 'desc')->first();
            // $output['value'] = $trade->id;
            // $output['text'] = $trade->trade_name;

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
        $data['jobCategory'] = EwVisaJobCategory::valid()->find($id);
        return view('recruitment.visaJobCategory.update', $data);
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
            'job_category_name' => 'required'
        ]);

        if ($validator->passes()) {
        EwVisaJobCategory::valid()->find($id)->update($input);
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
        EwVisaJobCategory::valid()->find($id)->delete();
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
}
