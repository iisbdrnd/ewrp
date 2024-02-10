<?php

namespace App\Http\Controllers\Provider\Tubingen;

use DB;
use File;
use Auth;
use Helper;
use Validator;
use App\Http\Requests;

use Illuminate\Http\Request;
use App\Model\JobOpening_provider;
use App\Model\JobCategory_provider;
use App\Http\Controllers\Controller;
use App\Model\JobOpeningAttachment_provider;

class JobOpeningController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.jobOpening.list', $data);
    }

    public function jobOpeningListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['jobs'] = JobOpening_provider::join('job_category', 'job_category.id', '=', 'job_opening.job_category_id')
            ->select('job_opening.*', 'job_category.name as category_name')
            ->where('job_opening.valid',1)
            ->where(function($query) use ($search)
            {
                $query->where('job_opening.title', 'LIKE', '%'.$search.'%')
                    ->orWhere('job_opening.updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.jobOpening.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['jobCategories'] = JobCategory_provider::valid()->get();
        return view('provider.eastWest.jobOpening.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];

        $validator = Validator::make($input, [
            'title'           => 'required',
            'job_category_id' => 'required',
            'job_type'        => 'required',
            'principal'       => 'required',
            'country'         => 'required',
            'gender'          => 'required',
            'age_from'        => 'required',
            'age_to'          => 'required',
            'deadline'        => 'required',
        ]);

        if ($validator->passes()) {
            $jobOpeningInfo = JobOpening_provider::create([
                "job_category_id" => $request->job_category_id,
                "job_type"        => $request->job_type,
                "title"           => $request->title,
                "principal"       => $request->principal,
                "country"         => $request->country,
                "gender"          => $request->gender,
                "age_from"        => $request->age_from,
                "age_to"          => $request->age_to,
                "religion"        => $request->religion,
                "experience"      => $request->experience,
                "education"       => $request->education,
                "duration"        => $request->duration,
                "salary"          => $request->salary,
                "job_description" => $request->job_description,
                "deadline"        => $request->deadline,
            ]);

            foreach ($fau_attachment as $index => $attachm) {
                JobOpeningAttachment_provider::create([
                    "job_opening_id"        => $jobOpeningInfo->id,
                    "attachment_name"       => $attachm,
                    "attachment_real_name"  => $fau_attachment_real_name[$index]
                ]);
            }

            $output['messege'] = 'Job has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        DB::commit();
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['jobCategories'] = JobCategory_provider::valid()->get();
        $data['jobOpening'] = JobOpening_provider::valid()->find($id);
        $data['jobAttFiles'] = JobOpeningAttachment_provider::valid()->where("job_opening_id", $id)->get();
        return view('provider.eastWest.jobOpening.update', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'title'           => 'required',
            'job_category_id' => 'required',
            'job_type'        => 'required',
            'principal'       => 'required',
            'country'         => 'required',
            'gender'          => 'required',
            'age_from'        => 'required',
            'age_to'          => 'required',
            'deadline'        => 'required'
        ]);

        if ($validator->passes()) {
            JobOpening_provider::valid()->find($id)->update([
                "job_category_id" => $request->job_category_id,
                "job_type"        => $request->job_type,
                "title"           => $request->title,
                "principal"       => $request->principal,
                "country"         => $request->country,
                "gender"          => $request->gender,
                "age_from"        => $request->age_from,
                "age_to"          => $request->age_to,
                "religion"        => $request->religion,
                "experience"      => $request->experience,
                "education"       => $request->education,
                "duration"        => $request->duration,
                "salary"          => $request->salary,
                "job_description" => $request->job_description,
                "deadline"        => $request->deadline,
            ]);

            $fau_attachment_id = (!empty($request->fau_attachment_id)) ? $request->fau_attachment_id : [];
            $fau_attachment_db = collect(JobOpeningAttachment_provider::valid()->where("job_opening_id", $id)->get()->pluck("id")->all());
            $fau_attachment_diff = $fau_attachment_db->diff($fau_attachment_id);

            $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
            $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];

            foreach($fau_attachment_diff as $fau_attachment_db_id) {
                $job_attachment = JobOpeningAttachment_provider::find($fau_attachment_db_id);
                File::delete(public_path('uploads/job_opening_attachments/'.$job_attachment->attachment_name));
                $job_attachment->delete();
            }

            foreach ($fau_attachment_id as $index => $fau_attachment_id) {
                if($fau_attachment_id==0 || !$fau_attachment_db->contains($fau_attachment_id)) {
                    JobOpeningAttachment_provider::create([
                        "job_opening_id"        => $id,
                        "attachment_name"       => $fau_attachment[$index],
                        "attachment_real_name"  => $fau_attachment_real_name[$index],
                    ]);
                }
            }
            $output['messege'] = 'Job has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        DB::commit();
        echo json_encode($output);
    }

    public function destroy($id)
    {
        $job_info = JobOpening_provider::valid()->find($id);
        if (!empty($job_info)) {
            $job_attachments = JobOpeningAttachment_provider::valid()->where('job_opening_id', $id)->get();

            if (!empty($job_attachments)) {
                foreach ($job_attachments as $key => $attachment) {
                    File::delete(public_path('uploads/job_opening_attachments/'.$attachment->attachment_name));
                    JobOpeningAttachment_provider::valid()->find($attachment->id)->delete();
                }
            }
            $job_info->delete();

        } else {
            echo 'No Job Found';
        }
        
    }
}
