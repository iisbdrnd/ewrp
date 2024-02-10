<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use DB, Auth, Helper;

//Model
use App\Model\SoftwareModules;
use App\Model\SoftwarePackage;
use App\Model\CrmPackage;
use App\Model\Icon;

class CrmPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Module';

        return view('softAdmin.crmPackage.index', $data);
    }

    public function crmPackageList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['package_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['crmPackage'] = CrmPackage::valid()->where(function($query) use ($search)
            {
                $query->where('package_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.crmPackage.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        
        
        return view('softAdmin.crmPackage.create');
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
        $input = array(
            "package_name"      => $request->package_name
        );

        $validator = Validator::make($input, [
            "package_name"      => "required"
        ]);

        if ($validator->passes()) {
            CrmPackage::create($input);
            $output['messege'] = 'Package has been created';
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
        // $data['softwareModules'] = SoftwareModules::valid()->get();
        $data['crmPackage'] = CrmPackage::valid()->find($id);

        return view('softAdmin.crmPackage.update', $data);
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
        $input = array(
            "package_name"      => $request->package_name
        );

        $validator = Validator::make($input, [
            "package_name"      => "required"
        ]);

        if ($validator->passes()) {
            CrmPackage::valid()->find($id)->update($input);
            $output['messege'] = 'Package has been updated';
            $output['msgType'] = 'success';
        }else {
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
        CrmPackage::valid()->find($id)->delete();
    }

   
}
