<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use File;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\License_provider;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.license.list', $data);
    }

    public function licenseListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['newsList'] = License_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.license.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.license.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
        
        if (count($fau_attachment) > 1) {
            $output['messege'] = 'You can\'t attach more then one attachment';
            $output['msgType'] = 'danger';
        }else{
            $validator = Validator::make($input, [
                'title' => 'required'
            ]);

            if ($validator->passes()) {
                License_provider::create([
                    'title'                => $request->title,
                    'description'          => $request->description,
                    "attachment_name"      => $fau_attachment[0], 
                    "attachment_real_name" => $fau_attachment_real_name[0]
                ]);
                
                $output['messege'] = 'License has been created';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['license'] = License_provider::valid()->find($id);
        return view('provider.eastWest.license.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];

        if (count($fau_attachment) > 1) {
            $output['messege'] = 'You can\'t attach more then one attachment';
            $output['msgType'] = 'danger';
        }else{
            $validator = Validator::make($input, [
                'title' => 'required'
            ]);

            if ($validator->passes()) {
                $license = License_provider::valid()->find($id);

                if ($license->attachment_name != $fau_attachment[0]) {
                    File::delete(public_path('uploads/license_attachments/'.$license->attachment_name));
                }else{
                    $license->update([
                        "attachment_name"      => $fau_attachment[0], 
                        "attachment_real_name" => $fau_attachment_real_name[0]
                    ]);
                }

                $license->update([
                    'title'       => $request->title,
                    'description' => $request->description,
                    "attachment_name"      => $fau_attachment[0], 
                    "attachment_real_name" => $fau_attachment_real_name[0]
                ]);


                // foreach($fau_attachment_diff as $fau_attachment_db_id) {
                //     $job_attachment = JobOpeningAttachment_provider::find($fau_attachment_db_id);
                //     File::delete(public_path('uploads/job_opening_attachments/'.$job_attachment->attachment_name));
                //     $job_attachment->delete();
                // }

                // foreach ($fau_attachment_id as $index => $fau_attachment_id) {
                //     if($fau_attachment_id==0 || !$fau_attachment_db->contains($fau_attachment_id)) {
                //         JobOpeningAttachment_provider::create([
                //             "job_opening_id"        => $id,
                //             "attachment_name"       => $fau_attachment[$index],
                //             "attachment_real_name"  => $fau_attachment_real_name[$index],
                //         ]);
                //     }
                // }

                $output['messege'] = 'License has been updated';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        License_provider::valid()->find($id)->delete();
    }
}
