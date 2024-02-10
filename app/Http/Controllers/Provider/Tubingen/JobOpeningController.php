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
            ->join('en_country','en_country.id', '=', 'job_opening.country_id')
            ->select('job_opening.*', 'job_category.name as category_name', 'en_country.name as country_name')
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
        $data['countries'] = DB::table('en_country')->get();
        $data['attachments'] = JobOpeningAttachment_provider::valid()->get();

        return view('provider.eastWest.jobOpening.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        // dd($input);
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
        
        if (count($fau_attachment) > 1) {
            $output['messege'] = 'You can\'t attach more then one attachment';
            $output['msgType'] = 'danger';
        }else{
            $validator = Validator::make($input, [
                'company_name'         => 'required',
                'job_category_id'      => 'required',
                'country_id'           => 'required',
                'accommodation_status' => 'required',
                'food_status'          => 'required',
                'age'                  => 'required'
            ]);
            if(isset($request->attachment_id)){
                $attachmentInfo = JobOpeningAttachment_provider::find($request->attachment_id);
            }
            if ($validator->passes()) {
                $jobOpeningInfo = JobOpening_provider::create([
                    "company_name"         => $request->company_name,
                    "job_category_id"      => $request->job_category_id,
                    "country_id"           => $request->country_id,
                    "quantity"             => $request->quantity,
                    "salary"               => $request->salary,
                    "accommodation_status" => $request->accommodation_status,
                    "food_status"          => $request->food_status,
                    "age"                  => $request->age,
                    "attachment_name"      => !empty($attachmentInfo) ? $attachmentInfo->attachment_name : (count($fau_attachment) > 0 ? $fau_attachment[0] : ''), 
                    "attachment_real_name" => !empty($attachmentInfo) ? $attachmentInfo->attachment_real_name :(count($fau_attachment_real_name) > 0 ? $fau_attachment_real_name[0] : '')
                ]);

                // foreach ($fau_attachment as $index => $attachm) {
                if(count($fau_attachment) > 0){
                    JobOpeningAttachment_provider::create([
                        "job_opening_id"        => $jobOpeningInfo->id,
                        "attachment_name"       => $fau_attachment[0],
                        "attachment_real_name"  => $fau_attachment_real_name[0]
                    ]);
                }
                // }

                $output['messege'] = 'Job has been created';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }
        
        DB::commit();
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['jobCategories'] = JobCategory_provider::valid()->get();
        $data['countries'] = DB::table('en_country')->get();
        $data['jobOpening'] = JobOpening_provider::valid()->find($id);
        $data['attachments'] = JobOpeningAttachment_provider::valid()->get();
        
        return view('provider.eastWest.jobOpening.update', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $output = array();
        $input = $request->all();
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];

        if (count($fau_attachment) > 1) {
            $output['messege'] = 'You can\'t attach more then one attachment';
            $output['msgType'] = 'danger';
        }else{
            $validator = Validator::make($input, [
                'company_name'         => 'required',
                'job_category_id'      => 'required',
                'country_id'           => 'required',
                'accommodation_status' => 'required',
                'food_status'          => 'required',
                'age'                  => 'required'
            ]);

            if(isset($request->attachment_id)){
                $attachmentInfo = JobOpeningAttachment_provider::find($request->attachment_id);
            }

            if ($validator->passes()) {
                $jobOpening = JobOpening_provider::valid()->find($id);
                // if(count($fau_attachment) > 0){
                //     // if ($jobOpening->attachment_real_name != $fau_attachment_real_name[0]) {
                //         // File::delete(public_path('uploads/job_opening_attachments/'.$jobOpening->attachment_name));
                //         $jobOpening->update([
                //             "attachment_name"      => !empty($attachmentInfo) ? $attachmentInfo->attachment_name : (count($fau_attachment) > 0 ? $fau_attachment[0] : ''), 
                //             "attachment_real_name" => !empty($attachmentInfo) ? $attachmentInfo->attachment_real_name :(count($fau_attachment_real_name) > 0 ? $fau_attachment_real_name[0] : '')
                //         ]);
                //     // }
                // }
                $jobOpening->update([
                    "company_name"         => $request->company_name,
                    "job_category_id"      => $request->job_category_id,
                    "country_id"           => $request->country_id,
                    "quantity"             => $request->quantity,
                    "salary"               => $request->salary,
                    "accommodation_status" => $request->accommodation_status,
                    "food_status"          => $request->food_status,
                    "age"                  => $request->age,
                    "publish_status"       => $request->publish_status,
                    "attachment_name"      => !empty($attachmentInfo) ? $attachmentInfo->attachment_name : (count($fau_attachment) > 0 ? $fau_attachment[0] : ''), 
                    "attachment_real_name" => !empty($attachmentInfo) ? $attachmentInfo->attachment_real_name :(count($fau_attachment_real_name) > 0 ? $fau_attachment_real_name[0] : '')
                ]);

                if(count($fau_attachment) > 0){
                    JobOpeningAttachment_provider::create([
                        "job_opening_id"        => $jobOpening->id,
                        "attachment_name"       => $fau_attachment[0],
                        "attachment_real_name"  => $fau_attachment_real_name[0]
                    ]);
                }

                // JobOpening_provider::valid()->find($id)->update([
                //     "company_name"         => $request->company_name,
                //     "job_category_id"      => $request->job_category_id,
                //     "country_id"           => $request->country_id,
                //     "quantity"             => $request->quantity,
                //     "salary"               => $request->salary,
                //     "accommodation_status" => $request->accommodation_status,
                //     "food_status"          => $request->food_status,
                //     "attachment_name"      => count($fau_attachment) > 0 ? $fau_attachment[0] : '', 
                //     "attachment_real_name" => count($fau_attachment_real_name) > 0 ? $fau_attachment_real_name[0] : ''
                // ]);




                // $fau_attachment_id = (!empty($request->fau_attachment_id)) ? $request->fau_attachment_id : [];
                // $fau_attachment_db = collect(JobOpeningAttachment_provider::valid()->where("job_opening_id", $id)->get()->pluck("id")->all());
                // $fau_attachment_diff = $fau_attachment_db->diff($fau_attachment_id);

                // $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
                // $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];

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
                $output['messege'] = 'Job has been updated';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }
        DB::commit();
        echo json_encode($output);
    }

    public function destroy($id)
    {
        $job_info = JobOpening_provider::valid()->find($id);
        if (!empty($job_info)) {
            // $job_attachments = JobOpeningAttachment_provider::valid()->where('job_opening_id', $id)->get();

            // if (!empty($job_attachments)) {
            //     foreach ($job_attachments as $key => $attachment) {
            //         File::delete(public_path('uploads/job_opening_attachments/'.$attachment->attachment_name));
            //         JobOpeningAttachment_provider::valid()->find($attachment->id)->delete();
            //     }
            // }
            $job_info->delete();

        } else {
            echo 'No Job Found';
        }
        
    }

    public function setInterview(Request $request){
        $data['job'] = JobOpening_provider::find($request->job_id);
        return view('provider.eastWest.jobOpening.setInterview', $data);
    }
    public function setInterviewAction(Request $request){
        $output = array();
        $input = $request->all();

        JobOpening_provider::valid()->find($request->job_id)->update([
            'interview_status' => $request->interview_status,
            'interview_date'   => $request->interview_date
        ]);
        
        $output['messege'] = 'Interview Set Successfully';
        $output['msgType'] = 'success';

        echo json_encode($output);
    }
}
