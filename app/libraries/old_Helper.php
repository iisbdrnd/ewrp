<?php
use Carbon\Carbon;
use App\Model\Medical;
use App\Model\Project;
use App\Model\EwTrades;
use App\Model\User_user;
use App\Model\EwProjects;
use App\Model\EwInterview;
use App\Model\EwCandidates;
use App\Model\EwReferences;
use App\Model\EwCandidatesCV;
use App\Model\EwMobilization;
use App\Model\EwPassportForm;
use App\Model\EwConfiguration;
use App\Model\EwInterviewCall;
use App\Model\SoftwareModules;
use App\Model\EwChartOfAccounts;
use App\Model\EwVisaJobCategory;
use App\Model\EwTicketBillMaster;
use Illuminate\Support\Facades\DB;
use App\Model\EwAccountTransaction;
use App\Model\MobilizationActivity;
use App\Model\SoftwareInternalLink;
use App\Model\AmsAssignNotification;
use App\Model\EwReportConfiguration;
use App\Model\EwCandidateTransaction;
use Illuminate\Support\Facades\Config;
use App\Model\EwCollectableAccountHeads;
use App\Model\EwMobilizationMasterTable;
use App\Model\EwMobilizeDependencyMaster;
use App\Http\Controllers\AdapterController;
use App\Model\ProjectMailConfiguration_user;
use App\Model\SoftwareInternalLinkAccess_user;
use App\Model\EwProjectTrades;


class Helper {

    public static function mailConfig($configId=0) {
        $mailConfig = '';
        $config = array();

        $mailConfig = ((Auth::user()->check())) ? ProjectMailConfiguration_user::valid()->first() : '';

        if(!empty($mailConfig)){
            $config = [
                'driver'        => ($mailConfig->mail_protocal==1) ? 'smtp' : 'mail',
                'host'          => $mailConfig->smtp_hostname,
                'port'          => $mailConfig->smtp_port,
                'from'          => [
                'address'       => $mailConfig->mail_from_email,
                'name'          => $mailConfig->mail_from_name
                ],
                'username'      => $mailConfig->smtp_username,
                'password'      => $mailConfig->smtp_password,
                'sendmail'      => '/usr/sbin/sendmail -bs',
                'pretend'       => false
           ];
           if(!empty($mailConfig->smtp_secure)) {
               $config['encryption'] = $mailConfig->smtp_secure;
           }
       } else {
           $config = [
                'driver'        => 'smtp',
                'host'          => 'smtp.gmail.com',
                'SMTPAuth'      => true,
                'port'          => 465,
                'from'          => [
                'address'       => 'noreplyewhr@gmail.com',
                'name'          => 'East West Human Resource Center Ltd.'
                ],
                'encryption'    => 'ssl',
                'username'      => 'noreplyewhr@gmail.com',
                'password'      => 'ohuwtiazzlhngjuz',
                'sendmail'      => '/usr/sbin/sendmail -bs',
                'pretend'       => false
           ];
       }
        Config::set('mail', $config);
        return $config;
    }

    public static function userAccess($route, $module='', $isResource=1) {
        $user_id = Auth::user()->get()->id;

        if(!empty($module)) {
            switch($module) {
                case('ew'):
                    $module_id = 1;
                    break;
                case('admin'):
                    $module_id = 2;
                    break;
                case('recruit'):
                    $module_id = 3;
                    break;
                default:
                    $module_id = 0;
                    break;
            }
            $module = SoftwareModules::active()->find($module_id);
            $route = ($isResource==1) ? @$module->route_prefix.@$module->url_prefix.'.'.$route : @$module->route_prefix.$route;
        }

        $access = DB::table('software_access')->where('user_id', $user_id)->where('route', $route)->first();
        if(empty($access)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function userPageAccess($request) {
        $user_id = Auth::user()->get()->id;
        $accessMenuId = $request->accessMenuId;
        $internalLink = SoftwareInternalLink::active()->where('menu_id', $accessMenuId)->get();
        $accessLink = SoftwareInternalLinkAccess_user::join('software_internal_link', 'software_internal_link_access.link_id', '=', 'software_internal_link.id')
            ->where('software_internal_link.menu_id', $accessMenuId)
            ->where('software_internal_link_access.user_id', $user_id)
            ->where('software_internal_link_access.valid', 1)
            ->get()->pluck('link_id')->all();

        $access = array();
        foreach($internalLink as $internalLink) {
            $routeName = explode('.', $internalLink->route);
            $lastIndex = count($routeName)-1;
            $access[$routeName[$lastIndex]] = in_array($internalLink->id, $accessLink);
        }

        return (object) $access;
    }

    public static function adminAccess($route, $isResource=1) {
        $admin_id = Auth::admin()->get()->id;
        $route = ($isResource==1) ? 'softAdmin.softAdmin.'.$route : 'softAdmin.'.$route;
        $access = DB::table('admin_access')->where('admin_id', $admin_id)->where('route', $route)->first();
        if(empty($access)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    //Asc Desc
    public static function ascDesc($data=[], $fieldList=[], $defaultValue=[]) {
        $defaultField = (empty($defaultValue) || empty($defaultValue[0])) ? 'id' : $defaultValue[0];
        $ascDesc = (empty($defaultValue) || empty($defaultValue[1])) ? 'desc' : $defaultValue[1];
        $asc = (empty($data) || empty($data['asc'])) ? 0 : $data['asc'];
        $desc = (empty($data) || empty($data['desc'])) ? 0 : $data['desc'];

        foreach($fieldList as $key => $field) { if(($asc==($key+1)) || ($desc==($key+1))) { $orderField = $field; } }
        if(!empty($orderField)) {
            if(!empty($asc) && ($asc!=0)) { $ascDesc="asc"; } else { $ascDesc="desc"; }
        } else {
            $orderField = $defaultField;
        }

        return [$orderField, $ascDesc];
    }

    //Pagination
    public static function paginate($data=[], $perPage=10) {
        $perPage = (empty($data) || empty($data['perPage'])) ? $perPage : $data['perPage'];
        $serial = (!empty($data) && !empty($data['page']) && ($data['page']>1)) ? ($perPage*($data['page']-1))+1 : 1;
        return (object) ['perPage' => $perPage, 'serial' => $serial];
    }

    //Validation Error
    public static function vError($validator) {
        $output = array();
        $output['messege'] = implode($validator->messages()->all('<i class="glyphicon glyphicon-ban-circle alert-icon validationErrIcon"></i><strong>:message</strong><br>'), '');
        $output['msgType'] = 'validationError';
        return $output;
    }

	public static function subStrFunc($content, $length) {
        if ($length < strlen($content)) {
            $content = substr($content, 0, $length);
            $content = explode(" ", $content);
            array_pop($content);
            return implode(" ", $content).'...';
        } else {
            return $content;
        }
    }

	//For showing file size
	public static function getFileThumb($file_ext) {
		if($file_ext=='doc' || $file_ext=='docx') {
			$thumb = url('/public/file_icon/doc.png');
		} else if($file_ext=='ppt' || $file_ext=='pptx') {
			$thumb = url('/public/file_icon/ppt.png');
		} else if($file_ext=='xls' || $file_ext=='xlsx') {
			$thumb = url('/public/file_icon/xls.png');
		} else if($file_ext=='zip' || $file_ext=='rar' || $file_ext=='tar') {
			$thumb = url('/public/file_icon/zip.png');
		} else if($file_ext=='pdf') {
			$thumb = url('/public/file_icon/pdf.png');
		} else if($file_ext=='csv') {
			$thumb = url('/public/file_icon/csv.png');
		} else if($file_ext=='txt') {
			$thumb = url('/public/file_icon/txt.png');
		} else {
			$thumb = url('/public/file_icon/zip.png'); //default
		}
		return $thumb;
	}

	//For showing file size
	public static function fileSizeConvert($bytes) {
		$bytes = floatval($bytes);
		$arBytes = array(
			0 => array(
				"UNIT" => "TB",
				"VALUE" => pow(1024, 4)
			),
			1 => array(
				"UNIT" => "GB",
				"VALUE" => pow(1024, 3)
			),
			2 => array(
				"UNIT" => "MB",
				"VALUE" => pow(1024, 2)
			),
			3 => array(
				"UNIT" => "KB",
				"VALUE" => 1024
			),
			4 => array(
				"UNIT" => "B",
				"VALUE" => 1
			),
		);
		foreach($arBytes as $arItem) {
			if($bytes >= $arItem["VALUE"]) {
				$result = $bytes / $arItem["VALUE"];
				$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
				break;
			}
		}
		return $result;
	}


	//For showing file size
	public static function getApiHashUrl($url, $allParameter) {
		$auth_key = Auth::user()->get()->auth_key;
		$secret_key = Auth::user()->get()->secret_key;
        if($auth_key && $secret_key) {
			$countParameter = 2;
			$hash = $auth_key;
			$requested_url = AdapterController::$nodeUrl.'/'.$url;
			$parameter = 'auth='.$auth_key;
			foreach($allParameter as $key => $par) {
				$hash .= $par;
				$parameter .= '&'.$key.'='.$par;
				$countParameter++;
			}
			$hash .= $secret_key;
			$hash = sha1(strtolower($hash));
			$parameter .= '&hash='.$hash;

			return (object) array('url' => $requested_url, 'parameter' => $parameter, 'parCount' => $countParameter);
		} else {
			return (object) array();
		}
	}

	public static function curlExecution($url, $data) {
		$urlData = self::getApiHashUrl($url, $data);

		if(!empty($urlData)) {
			$handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $urlData->url);
			curl_setopt($handle, CURLOPT_POST, $urlData->parCount);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $urlData->parameter);
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

			$result = curl_exec($handle);

			$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			if ($code == 200 && !( curl_errno($handle))) {
				return $result;
			} else {
				return '{}';
			}
		} else {
			return '{}';
		}
    }

    public static function getYMD($date) {
        if (!empty($date)) {
            $array = explode('/', $date);
            return date("Y-m-d", strtotime($array[2] . '-' . $array[1] . '-' . $array[0]));
        } else {
            return;
        }
    }

    public static function getDMY($date, $divide=false) {
        $divide = $divide!=''? $divide:'/';
        if (!empty($date) && $date != '0000-00-00 00:00:00' && $date != '0000-00-00') {
            $date = date("Y-m-d", strtotime($date));
            $array = explode('-', $date);
            return $array[2] . $divide . $array[1] . $divide . $array[0];
        } else {
            return;
        }
    }

    public static function dmyDateFormate($date){
        return date('d-m-Y', strtotime($date));
    }

    public static function getDurationFormat($start_date, $end_date) {
        $date1=date_create($start_date);
        $date2=date_create($end_date);
        $diff=date_diff($date1,$date2);
        $day = (int) $diff->format("%a");
        $hour = (int) $diff->format("%H");
        $minute = (int) $diff->format("%I");
        $duration_text = "";
        $duration_value = 0;
        if($day==7){ $duration_text .= "1 week "; $duration_value += $day*24*60; }
        else if($day>0) { $duration_text .= ($day==1) ? "1 day " : $day." days "; $duration_value += $day*24*60; }
        if($hour>0){ $duration_text .= ($hour==1) ? "1 hour " : $hour." hours "; $duration_value += $hour*60; }
        if($minute>0){ $duration_text .= ($minute==1) ? "1 minute" : $minute." minutes"; $duration_value += $minute; }
        return $duration_text;
    }

    public static function getTransactionInstrumentNo($payment_type=false) {
        $data = array();
        $transaction = EwAccountTransaction::valid()
                        ->orderBy('id', 'desc')
                        ->first();
        $transaction_no = (isset($transaction->transaction_no) && !empty($transaction->transaction_no)) ? $transaction->transaction_no+1 : 1;
        $data['transaction_no'] = $transaction_no;

        if($payment_type) { //for instrument_no
            /*switch($payment_type) {
                case 1:
                    $instrument = 'PV';
                    break;
                case 2:
                    $instrument = 'RV';
                    break;
                case 3:
                    $instrument = 'BPV';
                    break;
                case 4:
                    $instrument = 'BRV';
                    break;
                case 5:
                    $instrument = 'JV';
                    break;
                    break;
            }*/
            $transaction = EwAccountTransaction::valid()
                            ->where('transaction_status', $payment_type)
                            ->orderBy('id', 'desc')
                            ->first();
            /*if(isset($transaction->instrument_no) && !empty($transaction->instrument_no)) {
                $number = preg_replace("/[^0-9]{1,4}/", '', $transaction->instrument_no);
                $instrument_no = $instrument.($number+1);
            } else {
                $instrument_no = $instrument.'1';
            }*/
            $instrument_no = (isset($transaction->instrument_no) && !empty($transaction->instrument_no)) ? $transaction->instrument_no+1 : 1;
            $data['instrument_no'] = $instrument_no;
        }

        return $data;
    }

    public static function getCandidateTransactionNo() {
        $transaction = EwCandidateTransaction::valid()->orderBy('id', 'desc')->first();
        return (isset($transaction->transaction_no) && !empty($transaction->transaction_no)) ? $transaction->transaction_no+1 : 1;
    }

    public static function getCandidateBalance($candidate_id, $account_head=0) {
        $data = array();
        if($account_head>0) {
            $transaction = EwCandidateTransaction::valid()
                ->select(DB::raw('sum(receivable_amount) as receivable_amount, sum(paid_amount) as paid_amount, sum(received_amount) as received_amount, sum(less_amount) as less_amount'))
                ->where('candidate_id', $candidate_id)
                ->where('collectable_account', $account_head)
                ->first();
        } else {
            $transaction = EwCandidateTransaction::valid()
                ->select(DB::raw('sum(receivable_amount) as receivable_amount, sum(paid_amount) as paid_amount, sum(received_amount) as received_amount, sum(less_amount) as less_amount'))
                ->where('candidate_id', $candidate_id)
                ->first();
        }
        $transaction->balance = $transaction->receivable_amount-($transaction->received_amount+$transaction->less_amount-$transaction->paid_amount);
        return $transaction;
    }

    //Candidate Auto ID
    public static function getCandidateId() {
        $currentYear = date("Y");
        $year = substr($currentYear,2,2);
        $newId = $year.'0001';
        $candidateId = EwCandidates::valid()->where(DB::raw('year(created_at)'), $currentYear)->orderBy('id', 'desc')->first();
        return (isset($candidateId->candidate_id) && !empty($candidateId->candidate_id)) ? $candidateId->candidate_id+1 : $newId;
    }


    //Company Details
    public static function companyDetails() {
        $project_id = Auth::user()->get()->project_id;
        $companyDetails= Project::join('project_info', 'project.id', '=', 'project_info.project_id')
            ->select('project_info.*')
            ->where('project.id', $project_id)
            ->first();

        $html = '<p class="company_name">'.$companyDetails->company_name.'</p>
                    <p class="address">'.$companyDetails->address.'</p>
                    <p class="contact"> Phone: '.$companyDetails->office_phone.' Fax: '.$companyDetails->fax.'</p>';

        return $html;
    }

    //GET EW PROJECT NAME
    public static function projectName($project_id) {
        if($project_id==Null) {
            $project = 'All';
        } else if($project_id==0) {
            $project = 'Head Office';
        } else {
            $project = EwProjects::valid()->find($project_id)->project_name;
        }
        return $project;
    }

    public static function  country($id){
        $country=  DB::table('countries')->where('id', $id)->first();
        return $country->name;

    }

    public static function mobilization($id){
         $mobilizationDetails= EwMobilization::valid()->where('id', $id )->get();
         return $mobilizationDetails;
    }

    public static function single_mobilization($id){
        
        $singleMobilization = EwMobilization::valid()->where('id', $id)->first();
        return $singleMobilization;
    }

    public static function getMobilizationName($id){
        
        $singleMobilization = EwMobilization::valid()->where('id', $id)->first()->name;
        return $singleMobilization;
    }

    public static function mobilize_dependency($countryId, $mobilizeId)
    {
        $data = EwMobilizeDependencyMaster::valid()->where('project_country_id', $countryId)->where('mobilize_id', $mobilizeId)
            ->select('project_country_id', 'mobilize_id', 'mobilize_depended_on_id')
            ->first();
        return $data;
    }

    public static function single_mobilization_followUp($id){
        $singleMobilization = EwMobilization::valid()->where('id', $id)
        ->where('mobilize_action', 1)
        ->first();
        return $singleMobilization;
    }

    public static function single_mobilization_operation($id){
        $singleMobilization = EwMobilization::valid()->where('id', $id)
        ->where('mobilize_action', 2)
        ->first();
        return $singleMobilization;
    }
    

    public function projectConfigureMobilization($id){
       $configuration = EwConfiguration::where('ew_project_id', $id)->first(); 
       return $configuration;
    }
    public static function projects($id){
        $projects = EwProjects::valid()->where('id', $id)->first();
        return $projects;
    }

    public static function projectId($id){
         $projectId = EwProjects::valid()->where('id', $id)->first();
        return $projectId->id;
    }

    public static function reference($id){
        $reference = EwReferences::valid()->where('id', $id)->first();
        return $reference;
    }

    public static function getReference($id){
        $reference = EwReferences::valid()->where('id', $id)->select('reference_name', 'dealer')->first();
        return $reference;
    }

    public static function candidateList($id){
        $candidateLists = EwCandidatesCV::valid()->where('ew_project_id', $id)->get();
        return  $candidateLists;
    }

    public static function cvCompletenessStatus($id){
        $data = EwCandidatesCV::find($id);
        return $data->cv_completeness_status;
    }

    public static function flightCompleted($projectId, $candidateId){
        $data = EwMobilizationMasterTable::where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)
        ->where('flight_completed', 1)
        ->first();
        return $data->flight_completed;
    }

    public static function mobilizeFinalCompletedStatus($id){
        $data = EwMobilizationMasterTable::valid()->where('ew_candidatescv_id', $id)->first();
        if($data === null){
            return 0;
        }else{
            return $data->completeness;
        }   
    }

    public static function getCVId($candidateId) {
        $candidateId = ((int) $candidateId);
        $candidateIdLen = strlen($candidateId);
        if($candidateIdLen==1) { $candidateId = '000'.$candidateId; }
        else if($candidateIdLen==2) { $candidateId = '00'.$candidateId; }
        else if($candidateIdLen==3) { $candidateId = '0'.$candidateId; }
        return $candidateId;
    }

    public static function single_candidate($id){
        $single_candidate = EwCandidatesCV::valid()->where('id', $id)->first();
        return $single_candidate;
    }

    public static function candidates($candidateId){

         $candidate = EwCandidatesCV::whereIn('id', $candidateId) 
         ->get();
        return $candidate;
    }

    public function accountTransferred($projectId, $candidate_status){
        $data = EwCandidatesCV::where('ew_project_id', $projectId)->where('candidate_status', $candidate_status)->count();
        return $data;
    }

    public static function candidateApprovedStatus($projectId){
        $data = EwCandidatesCV::where('valid', 1)
        ->where('ew_project_id', $projectId)
        ->where('approved_status', 1)
        ->count();
        return $data;
    }

    public static function dealer($id){
        $dealer = DB::table('users')->where('valid', 1)->where('id', $id)->first();
        return $dealer;
    }

    public static function getDealerName($id){
        $dealer = DB::table('users')->where('valid', 1)->where('id', $id)->first()->name;
        return $dealer;
    }

    public static function interviewTable($projectId, $candidateId){
        $data = EwInterview::where('valid', 1)->where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->first();
        return $data;
    }

    public static function getInterviewSalary($projectId, $candidateId){
        $data = EwInterview::where('valid', 1)->where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->first()->salary;
        return $data;
    }

    public static function remarksData($projectId, $candidateId, $mobilizeId){
        $data = MobilizationActivity::where('ew_project_id', $projectId)->where('ew_candidatescv_id', $candidateId)->where('mobilization_id', $mobilizeId)->get();
        return $data;
    }

    public static function totalInterviewFace($projectId){
        $data = EwCandidatesCV::valid()
        ->where('ew_project_id', $projectId)
        ->count();
        return $data;
    }

    public static function totalProjectCandidates($projectId){
        $data = EwCandidatesCV::valid()
        ->where('ew_project_id', $projectId)
        ->where('result', 1)
        ->count();
        return $data;
    }

    public static function deployed($projectId, $candidateId){
        $data = EwMobilizationMasterTable::where('ew_project_id', $projectId)
        ->where('ew_candidatescv_id', $candidateId)
        ->where('flight_briefing_completed', 1)
        ->where('flight_completed', 1)
        ->exists();
        return $data;
    }

    public static function  noOfCandidates($projectId, $trade){
        $data = EwCandidatesCV::valid()
        ->where('ew_project_id', $projectId)
        ->where('selected_trade', $trade)
        ->count();
        return $data;
    }

    public static function  noOfTradeQty($projectId, $trade){
        $data = EwProjectTrades::valid()
        ->where('ew_project_id', $projectId)
        ->where('trade_id', $trade)
        ->first()->trade_qty;

        if($data == '')
        {
            $total_qty = 0;
        }
        else
        {
            $total_qty = $data;
        }
        return $total_qty;
    }

   
/* Mobilization complateness status */
    public static function mcStatus($projectId,$candidateId, $mobilizeId){
        switch ($mobilizeId) {
            case 1:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('medical_id', $mobilizeId)->first();

                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->medical_id;
                return $data;
                break;
            case 2:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('medical_online_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->medical_online_id;
                return $data;
                break; 
            case 3:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('medical_self_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->medical_self_id;
                return $data;
                break; 
            case 4:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('fit_card_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->fit_card_id;
                return $data;
                break; 
            case 5:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('mofa_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->mofa_id;
                return $data;
                break; 
            case 6:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('visa_document_sent_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->visa_document_sent_id;
                return $data;
                break; 
            case 7:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('embassy_submission_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->embassy_submission_id;
                return $data;
                break; 
            case 8:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('visa_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->visa_id;
                return $data;
                break; 
            case 9:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('visa_online_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->visa_online_id;
                return $data;
                break; 
            case 10:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('visa_print_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->visa_print_id;
                return $data;
                break; 
            case 11:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('visa_attached_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->visa_attached_id;
                return $data;
                break; 
            case 12:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('pcc_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->pcc_id;
                return $data;
                break; 
            case 13:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('gttc_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->gttc_id;
                return $data;
                break; 
            case 14:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('fingerprint_status_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->fingerprint_status_id;
                return $data;
                break; 
            case 15:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('bmet_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->bmet_id;
                return $data;
                break; 
            case 16:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('gamca_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->gamca_id;
                return $data;
                break;
            case 17:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('medical_slip_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->medical_slip_id;
                return $data;
                break;
            case 18:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('document_sent_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->document_sent_id;
                return $data;
                break;
            case 19:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('remedical_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->remedical_id;
                return $data;
                break;
            case 20:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('pcc_received_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->pcc_received_id;
                return $data;
                break;
            case 21:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('gttc_received_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->gttc_received_id;
                return $data;
                break;
            case 22:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('smartcard_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->smartcard_id;
                return $data;
                break;
            case 23:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('pta_request_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->pta_request_id;
                return $data;
                break;
            case 24:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('pta_received_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->pta_received_id;
                return $data;
                break;
            case 25:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('flight_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->flight_id;
                return $data;
                break;
            case 26:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('flight_briefing_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->flight_briefing_id;
                return $data;
                break;
            case 27:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('qvc_appointment_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->qvc_appointment_id;
                return $data;
                break;
            case 29:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('medical_call_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->medical_call_id;
                return $data;
                break;
            case 30:
                $data =[];
                $data['data'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->where('medical_sent_id', $mobilizeId)->first();
                $data['count'] = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->medical_sent_id;
                return $data;
                break;
            default:

                break;
        }
    }

    public function passportStatus($status){

        return $status == 1?'In Office':($status == 2?'Yes':($status == 3?'No':'Nothing'));  
    }

    public static function salarayAD($status){
       
        return $status == 1?'Yes':($status == 2?'No':'<span class="text-warning">Pending</span>');
    }

    public static function medicalActualStatus($status){
       
        return $status == 1?'Fit':($status == 2?'Unfit':($status == 3?'Remedical':($status == 4?'Fit Self':'')));
    }

    public static function fingerPrintStatus($status){
       
        return $status == 1?'Yes':($status == 2?'No':'<span class="text-warning">Pending</span>');
    } 


    // public function selectedTrade($tradeId){
    //     $trade = DB::table('ew_trades')->where('id', $tradeId)->first();
    //     return $trade;
    // }

    public static function interviewCallProject($id){
        $interview_call = EwInterviewCall::where('ew_project_id', $id)->first();
        return $interview_call;
    }

    public static function interviewSelection($id){
        if($id === null){
            return 0;
        }else{
            $data = EwInterview::valid()->where('ew_candidatescv_id', $id)->first();
            return $data->interview_selected_status;
        }
    }

    public static function singleTrade($id){
        if($id === null){
            return null;
        }else{
            $data = EwTrades::valid()->where('id', $id)->first();
            return $data;
        }
    }

    public static function getTradeName($id){
        if($id === null){
            return null;
        }else{
            $data = EwTrades::valid()->where('id', $id)->first()->trade_name;
            return $data;
        }
    }

    public static function interview_candidate_info($projectId, $ew_candidatescv_id){
        $data = EwInterview::where('ew_project_id', $projectId)
        ->where('ew_candidatescv_id', $ew_candidatescv_id)
        ->where('valid', 1)
        ->first();
        return $data;

    }

    public static function passportExpireMobilization($candidateId){
        $passportExp = EwCandidatesCV::join('ew_mobilization_master_tables','ew_candidatescv.id','=','ew_mobilization_master_tables.ew_candidatescv_id')
        ->where('ew_candidatescv.id', $candidateId)
        ->where('ew_mobilization_master_tables.flight_completed',0)
        ->first()
        ->passport_expired_date;

        $created = new Carbon($passportExp);
        $now = Carbon::now();
        $diffMonth =  $now->diffInMonths($created);

        if($created < $now){
                $difference = ($created->diff($now)->days < 1) ? "<p><a href='#' class='btn btn-danger btn-xs'>Today</a></p>" : "<p><a href='#' class='btn btn-danger btn-xs'>Expired</a></p>";
        }else{
            $difference = ($created->diff($now)->days < 1) ? "<p><a href='#' class='btn btn-danger btn-xs'>Today</a></p>"
               : $created->diffInMonths($now) > 6?" ":"<p><a href='#' class='btn btn-danger btn-xs'>".$created->diffInMonths($now)." Months</a></p>";
        }
        
         return $difference;
    }

    public static function medicalStatusMobilization($candidateId){
        $passportExp = EwCandidatesCV::join('ew_mobilization_master_tables','ew_candidatescv.id','=','ew_mobilization_master_tables.ew_candidatescv_id')
        ->where('ew_candidatescv.id', $candidateId)
        ->where('ew_mobilization_master_tables.flight_completed',0)
        ->where('ew_mobilization_master_tables.medical_completed',1)
        ->first()
        ->medical_expire_date;

        $created = new Carbon($passportExp);
        $now = Carbon::now();
        $diffMonth =  $now->diffInMonths($created);

        if($passportExp == "0000-00-00")
        {
            $difference = "<p><a href='#' class='btn btn-info btn-xs'>No Date Found</a></p>";
        }
        else
        {
            if($created < $now){
                 $difference = ($created->diff($now)->days < 1)
               ? "<p><a href='#' class='btn btn-danger btn-xs'>Today</a></p>"
               : "<p><a href='#' class='btn btn-danger btn-xs'>Expired</a></p>";
            }else{
                $difference = ($created->diff($now)->days < 1)
                   ? "<p><a href='#' class='btn btn-danger btn-xs'>Today</a></p>"
                   : $created->diff($now)->days > 30?"":"<p><a href='#' class='btn btn-danger btn-xs'>".$created->diff($now)->days." Days</a></p>";
            }
        }

         return $difference;
    }

    public static function visaAttachMobilization($candidateId){
        $passportExp = EwCandidatesCV::join('ew_mobilization_master_tables','ew_candidatescv.id','=','ew_mobilization_master_tables.ew_candidatescv_id')
        ->where('ew_candidatescv.id', $candidateId)
        ->where('ew_mobilization_master_tables.flight_completed',0)
        ->where('ew_mobilization_master_tables.visa_attached_completed',1)
        ->first()
        ->visa_attach_expiry_date;

        $created = new Carbon($passportExp);
        $now = Carbon::now();
        $diffMonth =  $now->diffInMonths($created);

        if($passportExp == "0000-00-00")
        {
            $difference = "<p><a href='#' class='btn btn-info btn-xs'>No Date Found</a></p>";
        }
        else
        {
            if($created < $now){
                 $difference = ($created->diff($now)->days < 1)
               ? "<p><a href='#' class='btn btn-danger btn-xs'>Today</a></p>"
               : "<p><a href='#' class='btn btn-danger btn-xs'>Expired</a></p>";
            }else{
                $difference = ($created->diff($now)->days < 1)
                   ? "<p><a href='#' class='btn btn-danger btn-xs'>Today</a></p>"
                   : $created->diff($now)->days > 20?"":"<p><a href='#' class='btn btn-danger btn-xs'>".$created->diff($now)->days." Days</a></p>";
            }
        }

         return $difference;
    }

    public static function passportExpired($candidateId){
        $passportExp = EwCandidatesCV::where('id', $candidateId)
        ->first()
        ->passport_expired_date;

        $created = new Carbon($passportExp);
        $now = Carbon::now();
        $diffMonth =  $now->diffInMonths($created);

        if($created < $now){
                 $difference = ($created->diff($now)->days < 1)
               ? 'today'
               : "<p class='text-danger'>Expired ".$created->diffForHumans($now)."</p>";
        }else{
            $difference = ($created->diff($now)->days < 1)
               ? 'today'
               : $created->diffInMonths($now) >= 8?"<p class='text-success'>Valid Till ".round($created->diffInMonths($now)/12)." Years</p>":"<p class='text-danger'>Expire After ".$created->diffInMonths($now)." Months</p>";
        }
        
         return $difference;
    }

    public static function passportExpiredInForm($id){
        $passportExp = EwPassportForm::where('id', $id)
        ->first()
        ->passport_expired_date;

        $created = new Carbon($passportExp);
        $now = Carbon::now();
        $diffMonth =  $now->diffInMonths($created);

        if($created < $now){
                 $difference = ($created->diff($now)->days < 1)
               ? 'today'
               : "<p class='text-danger'>Expired ".$created->diffForHumans($now)."</p>";
        }else{
            $difference = ($created->diff($now)->days < 1)
               ? 'today'
               : $created->diffInMonths($now) >= 8?"<p class='text-success'>Valid Till ".round($created->diffInMonths($now)/12)." Years</p>":"<p class='text-danger'>Expire After ".$created->diffInMonths($now)." Months</p>";
        }
        
         return $difference;
    }

    public static function medicalDetails($projectId,$candidateId){
        $medical = Medical::valid()
        ->where('ew_project_id', $projectId)
        ->where('ew_candidatescv_id', $candidateId)
        ->first();
        return $medical;
    }

    public static function singleJobCategory($id){
        $category = EwVisaJobCategory::valid()->where('id', $id)->first();
        return empty($category)?'Not found':$category; 
    }

    public static function checkAssignuser($projectId){
    $assignUsers =  EwProjects::where('valid', 1)->where('id', $projectId)->first()->assign_user;
    $assignUser = json_decode($assignUsers, true);
     
        if($assignUser[Auth::user()->get()->id] == Auth::user()->get()->id){
            
        }else{
            return "notAllowed";  
        }
    }

    public static function assignUser($userId){
        $user = DB::table('users')->where('id', $userId)->first()->name;
        return $user;
    }

    /**
    * COUNT COMPLETED MOBILIZATION
    * @param projectId, mobilizeId
    */

    public static function mobilizationCompleted($projectId, $projectCountryId, $mobilizeId){

        $total_flight = EwMobilizationMasterTable::where('ew_project_id', $projectId)
        ->where('flight_completed', 1)
        ->get()
        ->pluck('ew_candidatescv_id');

        $total_deployed = EwMobilizationMasterTable::where('ew_project_id', $projectId)
        ->where('flight_completed', 1)->count();

        $not_passes_candidates = EwCandidatescv::where('ew_project_id', $projectId)
        ->whereNotIn('result', [1])
        ->where('approved_status', 1)
        ->get()
        ->pluck('id');

        $check_dependency = EwMobilizeDependencyMaster::valid()
                            ->where('project_country_id',$projectCountryId)
                            ->where('mobilize_id',$mobilizeId)
                            ->first()->mobilize_depended_on_id;

        switch ($mobilizeId) {
            case 1:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1,4))
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->where(function ($query) use ($request,$check_dependency) {
                            if($check_dependency == 17)
                            {
                                $query->where('medical_slip_completed', 1);
                            }
                            else if($check_dependency == 30)
                            {
                               $query->where('medical_sent_completed', 1);
                            }
                            else
                            {

                            }
                        })
                        ->count();
                break;
            case 2:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('mofa_completed', 1)
                        ->where('medical_online_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 3:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_self_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 4:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('fit_card_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 5:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->whereIn('medical_actual_status', array(1, 4))
                        ->where('mofa_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 6:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->where('visa_document_sent_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 7:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('embassy_submission_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->where(function($query) use($request,$check_dependency){
                                if($check_dependency == 1)
                                {
                                    $query->whereIn('medical_actual_status', array(1, 4));
                                }else if($check_dependency == 2){
                                    $query->where('medical_online_completed', 1);
                                }else{

                                }
                            })
                        ->count();
                break; 
            case 8:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 9:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_online_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 10:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_print_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->where(function($query) use($request,$check_dependency){
                            if($check_dependency == 1)
                            {
                                $query->whereIn('medical_actual_status', array(1, 4));
                            }else if($check_dependency == 6){
                                $query->where('visa_document_sent_completed', 1);
                            }else{

                            }
                        })
                        ->count();
               
                break; 
            case 11:
                  $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                    ->where('visa_attached_completed', 1)
                    ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                    ->where(function($query) use($request,$check_dependency){
                            if($check_dependency == 7)
                            {
                                $query->where('embassy_submission_completed', 1);
                            }else if($check_dependency == 10){
                                $query->where('visa_print_completed', 1);
                            }else{

                            }
                        })
                    ->count();
               
                break; 
            case 12:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_sent_completed', 1)
                        ->where('pcc_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 13:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_sent_completed', 1)
                        ->where('gttc_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();

               
                break; 
            case 14:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                ->where('fingerprint_completed', 1)
                                ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                                ->where(function($query) use($request,$check_dependency){
                                    if($check_dependency == 10)
                                    {
                                        $query->where('visa_print_completed', 1);
                                    }else if($check_dependency == 11){
                                        $query->where('visa_attached_completed', 1);
                                    }else{

                                    }
                                })
                                ->count();
                break; 
            case 15:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('fingerprint_completed', 1)
                        ->where('bmet_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break; 
            case 16:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('gamca_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 17:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_sent_completed', 1)
                        ->where('medical_slip_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 18:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('document_sent_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 19:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_actual_status', 2)
                        ->where('remedical_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 20:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pcc_completed', 1)
                        ->where('pcc_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 21:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('gttc_completed', 1)
                        ->where('gttc_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 22:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('bmet_completed', 1)
                        ->where('smartcard_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 23:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('smartcard_completed', 1)
                        ->where('pta_request_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 24:

                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_received_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->where(function($query) use($request,$check_dependency){
                            if($check_dependency == 22)
                            {
                                $query->where('smartcard_completed', 1);
                            }else if($check_dependency == 23){
                                $query->where('pta_request_completed', 1);
                            }else{

                            }
                        })
                        ->count();
                             
                break;
            case 25:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('flight_briefing_completed', 1)
                        ->where('flight_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 26:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('pta_received_completed', 1)
                        ->where('flight_briefing_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 27:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('visa_online_completed', 1)
                        ->where('qvc_appointment_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
            case 28:
                $count = EwCandidatesCV::valid()->where('ew_project_id', $projectId)
                        ->where('approved_status', 1)
                        ->whereNotIn('id', $not_passes_candidates)
                        ->count();
               
                break;
            case 29:
              
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                                ->where('medical_call_completed',1)
                                ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                                ->where(function($query) use($request,$check_dependency){
                                    if($check_dependency == 27)
                                    {
                                        $query->where('qvc_appointment_completed', 1);
                                    }
                                    else if($check_dependency == 28)
                                    {
                                        
                                    }else{

                                    }
                                })
                                ->count();
                
               
                break;
            case 30:
                $count = EwMobilizationMasterTable::where('ew_project_id', $projectId)
                        ->where('medical_call_completed', 1)
                        ->where('medical_sent_completed', 1)
                        ->whereNotIn('ew_candidatescv_id', $not_passes_candidates)
                        ->count();
               
                break;
        }
        
        return $count;// - $not_passes_candidates;
    }

    /**
    * Count showing of today activity Hints: Matching Carbon::now() with invite date
    */

        //     DB::table('users')
        // ->join('contacts', function ($join) {
        //     $join->on('users.id', '=', 'contacts.user_id')->orOn(...);
        // })
        // ->get();

     public static function currentCandidateQue($projectId, $mobilizeId){
        switch ($mobilizeId) {
            case 1:
            
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.medical_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id','=', $projectId)
                  ->where('mobilization_activities.mobilization_id','=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.medical_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break;
            case 2:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.medical_online_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.medical_online_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 4:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.fit_card_received_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.fit_card_received_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 5:

                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.mofa_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.mofa_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;            
                return $data;
                break; 
            case 6:

                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_document_sent_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_document_sent_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 7:

                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.embassy_submission_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.embassy_submission_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 8:

            $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_online_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_online_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 9:

            $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 10:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_print_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_print_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 11:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_attached_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.visa_attached_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 12:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.pcc_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.pcc_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 13:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.gttc_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.gttc_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 14:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.fingerprint_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.fingerprint_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 15:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.bmet_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.bmet_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break; 
            case 16:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.gamca_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.gamca_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break;
            case 20:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.pcc_received_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.pcc_received_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break;
            case 21:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.gttc_received_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.gttc_received_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break;
            case 29:
                $current = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.medical_call_completed', '=', 0) 
                  ->where('mobilization_activities.invite_date', '=', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();

                $late = MobilizationActivity::join('ew_mobilization_master_tables', function($join) use ($projectId, $mobilizeId){
                  $join->on('ew_mobilization_master_tables.ew_candidatescv_id', '=', 'mobilization_activities.ew_candidatescv_id')
                  ->where('ew_mobilization_master_tables.medical_call_completed', '=', 0)
                  ->where('mobilization_activities.invite_date', '<', Carbon::now()->toDateString())
                  ->where('ew_mobilization_master_tables.ew_project_id', '=', $projectId)
                  ->where('mobilization_activities.mobilization_id', '=', $mobilizeId);
                })->count();
                $data['current'] = $current;
                $data['late'] = $late;
                return $data;
                break;
            
            default:

                break;
        }
    }

    public static function getAccountHead($id)
    {
        $data = EwCollectableAccountHeads::valid()->where('id', $id)->first();
        return $data;
    }

    public static function getChartOfAccountHead($id)
    {
        $data = EwChartOfAccounts::valid()->where('id', $id)->first();
        return $data;
    }

    public static function getMobilizeStage($projectId, $candidateId){
        $data = EwMobilizationMasterTable::valid() 
              ->where('ew_project_id', $projectId)
                ->where('ew_candidatescv_id', $candidateId)
                ->first()->total_completed;
        return $data;
    }

    public static function getMobilizeName($id){
        $data = EwMobilization::valid()->where('id', $id)->first()->name;
        return $data;
    }

    public static function  companyLogo($width,$height) {
        $url = asset("public/uploads/logo/EWLOGO.png");
        $html = '<center>
            <img src="'.$url.'" style="display: inline" width="'.$width.'" height="'.$height.'">
           </center>';

        return $html;
    }

}
