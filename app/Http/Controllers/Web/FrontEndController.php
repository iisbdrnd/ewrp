<?php

namespace App\Http\Controllers\Web;

use DB;
use Auth;
use Hash;
use Mail;
use Helper;
use DateTime;
use Response;
use Validator;
use DateInterval;
use App\Model\Career_web;
use App\Model\Counter_web;
use App\Model\License_web;
use App\Model\ContactUs_web;
use App\Model\TubBanner_web;
use App\Model\TubClient_web;
use Illuminate\Http\Request;
use App\Model\JobOpening_web;
use App\Model\TubAboutUs_web;
use App\Model\TubGallery_web;
use App\Model\TubProduct_web;
use App\Model\JobCategory_web;
use App\Model\WhyChooseUs_web;
use App\Model\TubCustomers_web;
use App\Model\ProductCategory_web;
use App\Model\TrainingFacility_web;
use App\Http\Controllers\Controller;
use App\Model\ProductAttachment_web;
use App\Model\TermsAndCondition_web;
use App\Model\TubManagementTeam_web;
use App\Model\TubPhotoGallaries_web;
use App\Model\TubSustainability_web;
use App\Model\HeadOfficeFacility_web;
use App\Model\TubOperationalTeam_web;
use App\Model\JobOpeningAttachment_web;
use App\Model\FacilityHeadOfficeGallery_web;
use App\Model\TrainingFacilityGalleries_web;
use App\Model\TubSustainabilityCategory_web;
use App\Model\CompanyHistory_web;
use App\Model\NoticeBoardCategory_web;
use App\Model\NoticeBoard_web;
use App\Model\NewsEvent_web;

class FrontEndController extends Controller {
    public function home() {
        $data['banners']=TubBanner_web::valid()->get();
        $data['jobs']=JobOpening_web::valid()->orderBy('id', 'DESC')->limit(6)->get();
        $data['galleryPhotos'] = TubGallery_web::join('tub_photo_galleries', 'tub_photo_galleries.id', '=', 'tub_gallary.photo_gallery_id')
            ->select('tub_gallary.*', 'tub_photo_galleries.slug')
            ->where('tub_gallary.valid',1)
            ->orderBy('id', 'DESC')
            ->limit(12)
            ->get();
        $data['clients']=TubCustomers_web::valid()->get();
        $data['counters']=Counter_web::valid()->get();

        return view('web.home', $data);
	}

    public function managementTeam() {
        $data['managementTeams'] = TubManagementTeam_web::valid()->orderBy('sl_no')->get();
        return view('web.managementTeam', $data);
	}

    public function operationalTeam() {
        $data['operationalTeams'] = TubOperationalTeam_web::valid()->orderBy('sl_no')->get();
        return view('web.operationalTeam', $data);
	}
    
    public function ourCustomers() {
        $data['ourCustomers'] = TubCustomers_web::valid()->orderBy('sl_no', 'asc')->get();
        return view('web.ourCustomers', $data);
	}

    public function whyChooseUs() {
        $data['chooseUs'] = WhyChooseUs_web::valid()->first();
        return view('web.whyChooseUs', $data);
	}

    public function companyHistory() {
        $data['companyHistory'] = CompanyHistory_web::valid()->first();

        return view('web.companyHistory', $data);
	}
    
    public function facilities() {
        $data['trainingFacilities'] = TrainingFacility_web::where('valid', 1)->get();
        $data['trainingFacilityPhotos'] = TrainingFacilityGalleries_web::where('valid',1)->get();
        $data['headOfficeFacility'] = HeadOfficeFacility_web::where('valid', 1)->first();
        $data['headOfficePhotos'] = FacilityHeadOfficeGallery_web::where('valid',1)->get();
        
        $data['trainingFacilities'] = TrainingFacility_web::valid()->get();
        
        return view('web.facilities', $data);
	}
    public function services() {
        // $data['banner']=TubBanner_web::valid()->first();
        // $data['aboutUs'] = TubAboutUs_web::valid()->first();

        return view('web.services');
	}
    public function missionVision() {
        // $data['banner']=TubBanner_web::valid()->first();
        // $data['aboutUs'] = TubAboutUs_web::valid()->first();

        return view('web.missionVision');
	}
    public function ourClients() {
        $data['country_ids'] = $country_ids = TubCustomers_web::join('en_country', 'en_country.id', '=', 'tub_customers.country_id')
            ->select('tub_customers.id', 'tub_customers.country_id', 'en_country.name as country_name', 'en_country.iso')
            ->orderBy('en_country.sl_no')
            ->groupBy('tub_customers.country_id')
            ->get();
        if (count($country_ids) > 0) {
            foreach ($country_ids as $key => $country_data) {
                $country_data->clients = TubCustomers_web::valid()->where('country_id', $country_data->country_id)->orderBy('sl_no')->get();
            }
        } 
        // else {
        //     $country_id->clients = [];
        // }
        
        return view('web.ourClients', $data);
	}
    public function galleryAlbum() {
        $data['albums'] = TubPhotoGallaries_web::valid()->orderBy('sl_no')->get();

        return view('web.galleryAlbum', $data);
	}
    public function galleryPhotos($gallery_id){
        $data['album'] = TubPhotoGallaries_web::valid()->where('id', $gallery_id)->first();
        $data['galleryPhotos'] = TubGallery_web::valid()->where('photo_gallery_id', $gallery_id)->orderBy('sl_no')->get();
        
        return view('web.gallery', $data);
    }

    public function jobOpening(Request $request) {
        $category_id = $request->category_id;
        $data['jobCategories'] = $jobCategories = JobCategory_web::valid()->get();
        if (!empty($jobCategories)) {
            foreach($jobCategories as $category){
                $category->total_job = JobOpening_web::valid()->where('job_category_id', $category->id)->count();
            }
        }
        $data['jobs'] = $jobs = JobOpening_web::join('job_category', 'job_category.id','=', 'job_opening.job_category_id')
            ->join('en_country','en_country.id', '=', 'job_opening.country_id')
            ->select('job_opening.*', 'job_category.name as category_name', 'en_country.name as country_name')
            ->where(function($query) use ($category_id)
            {
                if(isset($category_id)){
                    $query->where('job_opening.job_category_id', $category_id);
                }
            })
            ->where('job_opening.valid', 1)
            ->where('job_opening.publish_status', 1)
            ->orderBy('job_opening.interview_status', "ASC")
            ->get();

        return view('web.jobOpening', $data);
	}

    public function viewAjaxJobList(Request $request) {
        $category_id = $request->category_id;
        // $data['jobCategories'] = $jobCategories = JobCategory_web::valid()->get();
        // if (!empty($jobCategories)) {
        //     foreach($jobCategories as $category){
        //         $category->total_job = JobOpening_web::valid()->where('job_category_id', $category->id)->count();
        //     }
        // }
            
        $data['jobs'] = $jobs = JobOpening_web::join('job_category', 'job_category.id','=', 'job_opening.job_category_id')
            ->join('en_country','en_country.id', '=', 'job_opening.country_id')
            ->select('job_opening.*', 'job_category.name as category_name', 'en_country.name as country_name')
            ->where(function($query) use ($category_id)
            {
                if(isset($category_id)){
                    $query->where('job_opening.job_category_id', $category_id);
                }
            })
            ->where('job_opening.valid', 1)
            ->where('job_opening.publish_status', 1)
            ->orderBy('job_opening.interview_date', 'ASC')
            ->get();

        return view('web.viewAjaxJobList', $data);
	}

    public function jobDetails($job_opening_id){
        $data['job'] = JobOpening_web::join('job_category', 'job_category.id','=', 'job_opening.job_category_id')
            ->where('job_opening.id', $job_opening_id)
            ->where('job_opening.valid', 1)
            ->first();

        return view('web.jobDetails', $data);
    }

    public function license(Request $request)
    {
        $license_id = $request->license_id;
        $data['licenseList'] = License_web::select('id', 'title')->valid()->get();
        $data['licenses'] = License_web::where(function($query) use ($license_id)
        {
            if(isset($license_id)){
                $query->where('id', $license_id);
            }
        })
        ->valid()
        ->get();

        return view('web.license', $data);
    }
    public function viewSingleLicense(Request $request)
    {
        $license_id = $request->license_id;

        $data['licenses'] = License_web::where(function($query) use ($license_id){
            if(isset($license_id)){
                $query->where('id', $license_id);
            }
        })
        ->valid()
        ->get();

        return view('web.viewSingleLicense', $data);
    }

    public function allJobCategory(){
        $data['jobCategories'] = $jobCategories = JobCategory_web::valid()->get();
        foreach($jobCategories as $category){
            $category->total_job = JobOpening_web::valid()->where('job_category_id', $category->id)->count();
        }

        return view('web.allJobCategory', $data);
    }

    public function termsAndConditions()
    {
        $data['termsAndCondition'] = TermsAndCondition_web::valid()->first();
        return view('web.termsAndConditions', $data);
    }

    public function noticeBoard(Request $request)
    {
        $category_id = $request->category_id;
        $data['noticeCategories'] = $noticeCategories = NoticeBoardCategory_web::valid()->orderBy('sl_no')->get();
        $data['newsEvents'] = NewsEvent_web::valid()->orderBy('id', 'DESC')->get();
        if (!empty($noticeCategories)) {
            foreach($noticeCategories as $category){
                $category->total_job = NoticeBoard_web::valid()->where('notice_board_category_id', $category->id)->count();
            }
        }
        $data['notices'] = $jobs = NoticeBoard_web::join('notice_board_category', 'notice_board_category.id','=', 'notice_board.notice_board_category_id')
            ->select('notice_board.*', 'notice_board_category.name as category_name')
            ->where(function($query) use ($category_id)
            {
                if(isset($category_id)){
                    $query->where('notice_board.notice_board_category_id', $category_id);
                }
            })
            ->where('notice_board.valid', 1)
            ->orderBy('notice_board.id', 'DESC')
            ->get();

        return view('web.noticeBoard.noticeBoard', $data);
    }
    public function viewAjaxNotice(Request $request) {
        $category_id = $request->category_id;
        
        $data['notices'] = NoticeBoard_web::join('notice_board_category', 'notice_board_category.id','=', 'notice_board.notice_board_category_id')
            ->select('notice_board.*', 'notice_board_category.name as category_name')
            ->where(function($query) use ($category_id)
            {
                if(isset($category_id)){
                    $query->where('notice_board.notice_board_category_id', $category_id);
                }
            })
            ->where('notice_board.valid', 1)
            ->orderBy('notice_board.id', 'DESC')
            ->get();
        return view('web.viewAjaxNoticeList', $data);
	}







    public function organizationChart() {
        $data['managementTeams'] = TubManagementTeam_web::valid()->orderBy('sl_no')->get();

        return view('web.organizationChart', $data);
	}
    public function contact() {
        $data['contactUs'] = ContactUs_web::valid()->first();

        return view('web.contact', $data);
	}
    public function contactUsAction(Request $request){
        $input = $request->all();
        $output = [];
        $validator = Validator::make($request->values, [
            "name"    => "required",
            "email"   => "required|email",
            "contact" => "required",
            "subject" => "required",
            "message" => "required"
        ]);
        if ($validator->passes()) {
            $gRecaptcha = $request->values['gRecaptcha'];
            if (isset($gRecaptcha)) {
                Helper::mailConfig();
                $email_data['data'] = [
                    'name'    => $request->values['name'],
                    'email'   => $request->values['email'],
                    'contact'   => $request->values['contact'],
                    'subject' => $request->values['subject'],
                    'message' => $request->values['message']
                ];
                // dd($request->values['email']);
                $contactUs = ContactUs_web::valid()->first()->head_office_email;
                // $contactUs = 'rudra1055@gmail.com';

                $email = $request->values['email'];
                $name = $request->values['name'];
                $message = $request->values['message'];
                
                $mail = Mail::send('emails.contact_us', $email_data, function($message) use ($request, $email, $name, $contactUs)
                {
                    $message->subject("New contact message from ". $name);
                    $message->to($contactUs, "East West");
                });
                $output['status'] = 1;
                $output['message'] = 'Email has been sent successfully';
            } else {
                $output['status'] = 0;
                $output['message'] = 'Please make sure you are not a robot!';
            }
        } else {
            $output['status'] = 0;
            $output['message'] = 'Please fillup the informations correctly.';
        }
        return response()->json($output);   
    }
    
    public function career() {
        $data['careers'] = Career_web::valid()->get();
        return view('web.career', $data);
	}
    
    public function allSustainability() {
        $data['sustainabilities'] = TubSustainability_web::valid()->get();

        return view('web.allSustainability', $data);
	}
    public function sustainability() {
        $url = request()->segment(count(request()->segments()));
        $sustainabilityCategoryId = TubSustainabilityCategory_web::where('url', $url)->valid()->first()->id;
        $data['sustainabilities'] = TubSustainability_web::where('sustainability_category_id', $sustainabilityCategoryId)->valid()->get();

        return view('web.sustainability', $data);
	}

    public function viewSustainabilityDocs(Request $request)
    {
        $attachmentData = TubSustainability_web::find($request->id);
        $path = public_path('uploads/sustainabilities/'.$attachmentData->attachment_name);
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Sustainability"'
        ]);
    }

    public function allProduct() {
        $data['products'] = TubProduct_web::valid()->get();
        
        return view('web.allProduct', $data);
	}

    public function product() {
        $url = request()->segment(count(request()->segments()));
        $productId = TubProduct_web::where('url', $url)->valid()->first()->id;
        
        $data['product'] = $products = TubProduct_web::where('id', $productId)->valid()->first();
        $data['universalFiles'] = ProductAttachment_web::where('product_id', $productId)->where('attachment_type', 1)->get();
        $data['secretFiles'] = ProductAttachment_web::where('product_id', $productId)
            ->where(function ($query) {
                $query->where('attachment_type', '=', 2)
                      ->orWhere('attachment_type', '=', 3);
            })
            ->get();
        return view('web.product', $data);
	}
    public function contactUs()
    {
        $data['contactUs'] = ContactUs_web::valid()->first();
        return view('web.contactUs', $data);
    }

    public function contactAction(Request $request) {
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            "name"    => "required",
            "email"   => "required|email",
            "subject" => "required",
            "message" => "required"
        ]);

        if ($validator->passes()) {
            Helper::mailConfig();
			$email_data['data'] = [
                'name'    => $request->name,
                'email'   => $request->email,
                'subject' => $request->subject,
                'message' => $request->message
            ];
            $contactUs = ContactUs_web::valid()->first()->email;

			$email = $request->email;
			$name = $request->name;

            $mail = Mail::send('emails.contact_us', $email_data, function($message) use ($request, $email, $name, $contactUs)
            {
                $message->from($email, $name);
                $message->subject("New contact message from". $name);
                $message->to($contactUs, "Tubingen");
            });
			
            return redirect()->route('contactUs')->with(['vError'=>'<strong>Your message has been sent</strong>']);    
        } else {
            return redirect()->route('contactUs')->with(['vError'=>implode($validator->messages()->all('<strong>:message</strong><br>'))]);
        }
    }


    public function gallery(Request $request)
    {
        $data['galleryGroups'] = TubPhotoGallaries_web::valid()->get();
        if(isset($request->photo_gallery_id)){
            $data['galleries'] = TubGallery_web::valid()->where('photo_gallery_id', $request->photo_gallery_id)->get();
        }else{
            $data['galleries'] = TubGallery_web::valid()->get();
        }
        // return view('web.termsAndConditions', $data);
        return view('web.gallery', $data);
    }
    public function galleryForGroup(Request $request, $galleryName)
    {
        // $lowerName = strtolower($galleryName);
        // $removeSpaces = str_replace(' ', '', $lowerName);
        // dd($removeSpaces);
        $data['galleries'] = TubGallery_web::valid()->where('photo_gallery_id', $request->id)->get();
        // return view('web.termsAndConditions', $data);
        return view('web.galleryForGroup', $data);
    }

    public function viewProductUniversalDocument(Request $request, $productName){
        $attachmentData = ProductAttachment_web::find($request->id);
        $path = public_path('uploads/products/'.$attachmentData->attachment_name);
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$productName.'"'
        ]);
    }

    public function viewProductSecretDocument(Request $request, $productName){
        if(Auth::guard('web')->check()){       
            $attachmentData = ProductAttachment_web::find($request->id);
            $path = public_path('uploads/products/'.$attachmentData->attachment_name);
            
            return Response::make(file_get_contents($path), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$productName.'"'
            ]);
        }else{
            return redirect()->route('login');
        }
    }

    public function categoryProduct(Request $request, $url)
    {
        $url = request()->segment(count(request()->segments()));
        $productCategoryId = ProductCategory_web::where('url', $url)->valid()->first()->id;
        
        $data['categoryProducts'] = TubProduct_web::where('product_category_id', $productCategoryId)->valid()->get();
        // dd(empty($data));
        return view('web.categoryProduct', $data);
    }

    public function register()
    {
        return view('web.register');
    }
    
    public function registerAction(Request $request)
    {
        DB::beginTransaction();
        $input = $request->all();

        $validator = Validator::make($input, [
            'name'           => 'required',
            'email'          => 'required',
            'mobile'         => 'required',
            'company'        => 'required',
            // "department_id"  => "required",
            // "designation_id" => "required",
        ]);
        if ($validator->passes()) {

            // $account_id = self::getAccountId();

            $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
            $original_string = implode("", $original_string);
            $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);

            $generalUserInfo = TubClient_web::create([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'mobile'                => $request->mobile,
                'company'               => $request->company,
                "verification_code"     => $verification_code,
                "project_id"            => 1,
                "status"                => 'Active',
                "valid"                 => 1
            ]);
            
            //send mail
            Helper::mailConfig();
            // $email_data['company_name'] = @Helper::getCompanyInfo(Auth::guard('provider')->user()->project_id)->company_name;

            // $email_data['poject_logo'] = @Helper::getCompanyInfo(Auth::guard('provider')->user()->project_id)->logo;

            $email_data['data'] = [
                'name'              =>  $request->name,
                'email'             =>  $request->email,
                'mobile'            =>  $request->mobile,
                'company_name'      =>  $request->company
                // 'logo'              =>  $projectInfo->logo,
                // 'website'           =>  $projectInfo->website
            ];
            $email = $request->email;
            $name  = $request->name;

            $email_data['link'] = url('email_verification?token=' . $verification_code);
            Mail::send('emails.email_verification', $email_data, function($message) use ($email, $name)
            {
                $message->subject('Email Verification');
                $message->to($email, $name);
            });
            
            $output['messege'] = 'Account Create Successful! You will get email after approved!';
            $output['msgType'] = 'success';
            $output['status'] = 1;
        } else {
            $output['status'] = 0;
            $output = Helper::vError($validator);
        }
        DB::commit();
        return response($output);
    }
    
    //START AUTO ACCOUNT ID GENERATE
    public static function getAccountId() {
        $currentDate = date("Ymd");
        $currentDate2 = date("Y-m-d");
        // $accountId = EnCorporateAccount_Web::where(DB::raw('DATE(created_at)'), $currentDate2)->orderBy('id', 'desc')->first();
        $accountId = EnCorporateAccount_Web::orderBy('id', 'desc')->first();

        if ($accountId) {
            $autoAccountId = $accountId->account_id+1;
        }else{
            $autoAccountId = $currentDate.'1';
        }
        return $autoAccountId;
    }
    //END AUTO ACCOUNT ID GENERATE

}
