<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EwReferences;
use App\Model\EwCandidates;

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
        
        return view('ew.reference.list', $data);
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

        return view('ew.reference.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('ew.reference.create', $data);
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
            'reference_name' => 'required',
            'reference_phone' => 'required'
        ]);

        if ($validator->passes()) {
            EwReferences::create($input);
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
        $data['ewReferences'] = EwReferences::valid()->find($id);
        return view('ew.reference.update', $data);
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
            'reference_phone'   => 'required'
        ]);

        if ($validator->passes()) {
        EwReferences::valid()->find($id)->update($input);
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


    //PROJECT WISE REFERENCES
    public function projectReferences(Request $request)
    {
        $data['inputData'] = $request->all();
        $project_id = $request->project_id;

        if ($project_id>0) {
            $data['references'] = EwCandidates::join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
                ->select('ew_references.*')
                ->where('ew_candidates.ew_project_id', $project_id)
                ->get();
        } else {
            $data['references'] = EwReferences::valid()->get();
        }

        return view('ew.reference.projectReferences', $data);
    }
}
