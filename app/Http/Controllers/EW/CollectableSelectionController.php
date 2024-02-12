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
use App\Model\EwProjectCollectableSelection;
use App\Model\EwProjects;
use App\Model\EwCollectableAccountHeads;

class CollectableSelectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('ew.collectableSelection.list', $data);
    }

    public function collectableSelectionListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::userPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['project_name', 'account_head']);
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

        foreach($projects as $project){
            $project->collectableAccountHeads = EwProjectCollectableSelection::join('ew_collectable_account_heads', 'ew_project_collectable_selection.collectable_account_id', '=', 'ew_collectable_account_heads.id')
            ->select('ew_collectable_account_heads.account_head')
            ->where('ew_project_collectable_selection.ew_project_id', $project->id)
            ->where('ew_project_collectable_selection.valid', 1)
            ->get()
            ->implode('account_head', ', ');
        }

        $data['projects'] = $projects;

        return view('ew.collectableSelection.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['projects'] = EwProjects::valid()->get();
        $data['collectableAccountHeads'] = EwCollectableAccountHeads::valid()->get();

        return view('ew.collectableSelection.create', $data);
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
        $project_id = $request->project_id;
        $collectable_account_head = $request->collectable_account_id;
        
        $validator = Validator::make($input, [
            'project_id'                => 'required',
            'collectable_account_id'    => 'required'
        ]);

        $assigned = EwProjectCollectableSelection::valid()->where('ew_project_id', $project_id)->first();
        if(empty($assigned)){
            if ($validator->passes()) {
                foreach($collectable_account_head as $collectable_account_id){
                    EwProjectCollectableSelection::create(['ew_project_id' => $project_id, 'collectable_account_id' => $collectable_account_id]);
                }
                $output['messege'] = 'Collectable selection has been created';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }else{
            $output['messege'] = 'This project collectable account head already assigned!';
            $output['msgType'] = 'danger';
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
        $data['project_id'] = $id;
        $data['projects'] = EwProjects::valid()->get();
        $data['collectableAccountHeads'] = EwCollectableAccountHeads::valid()->get();
        $data['project_for_collectable_accounts'] = EwProjectCollectableSelection::valid()->select('collectable_account_id')->where('ew_project_id', $id)->get()->pluck('collectable_account_id')->all();

        return view('ew.collectableSelection.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $output = array();
        $input = $request->all();
        $project_id = $request->project_id;
        $collectable_account_heads = $request->collectable_account_id;

        $validator = Validator::make($input, [
            'project_id'                => 'required',
            'collectable_account_id'    => 'required'
        ]);

        if($validator->passes()) {
            $selected_collectable_account = collect(EwProjectCollectableSelection::valid()->where('ew_project_id', $project_id)->get()->pluck('collectable_account_id')->all());
            $collectable_account_diff = $selected_collectable_account->diff($collectable_account_heads);

            if(!empty($collectable_account_diff)){
                $project_selected_collectable_accounts = EwProjectCollectableSelection::valid()->whereIn('collectable_account_id', $collectable_account_diff)->where('ew_project_id', $project_id)->get();
                foreach($project_selected_collectable_accounts as $project_selected_collectable_account){
                    EwProjectCollectableSelection::valid()->find($project_selected_collectable_account->id)->delete();
                }
            }
            if(!empty($collectable_account_heads)){
                foreach($collectable_account_heads as $collectable_account_head){
                    if(!$selected_collectable_account->contains($collectable_account_head)){
                        EwProjectCollectableSelection::create(['ew_project_id'=>$project_id, 'collectable_account_id'=>$collectable_account_head]);
                    }
                }
            }
            $output['messege'] = 'Collectable selection has been updated';
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
        //
    }


    
}
