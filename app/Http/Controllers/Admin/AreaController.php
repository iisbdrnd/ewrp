<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\JobArea_user;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('admin.area.list', $data);
    }

    public function areaListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['area_name', 'area_details', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['jobArea'] = JobArea_user::valid()
            ->where(function($query) use ($search)
            {
                $query->where('area_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('area_details', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('admin.area.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('admin.area.create', $data);
    }

    public function add()
    {
        return view('admin.area.add');
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
            'area_name'         => 'required',
            'area_details'      => 'required'
        ]);
        if ($validator->passes()) {
            JobArea_user::create($input);
            $output['messege'] = 'Area has been created';
            $output['msgType'] = 'success';

            //For In Page Add
            $areaInfo = JobArea_user::valid()->orderBy('id', 'desc')->first();
            $output['value'] = $areaInfo->id;
            $output['text'] = $areaInfo->area_name;
            $output['area_details'] = $areaInfo->area_details;
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
        $data['jobArea'] = JobArea_user::valid()->find($id);
        return view('admin.area.update', $data);
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

        $validator = Validator::make($input, [
            'area_name'         => 'required',
            'area_details'      => 'required'
        ]);

        if ($validator->passes()) {
        JobArea_user::valid()->find($id)->update($input);
        $output['messege'] = 'Area has been updated';
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
        JobArea_user::valid()->find($id)->delete();
    }
}
