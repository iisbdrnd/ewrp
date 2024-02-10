<?php
namespace App\libraries;

use Illuminate\Support\Facades\Config;
use Auth;
use DB;
use File;
use Image;



use App\Http\Controllers\AdapterController;
use App\Model\CrmBackOfficeSetup;
use App\Model\SoftwareModules;
use App\Model\SoftwareInternalLink;
use App\Model\SoftwareInternalLink_provider;
use App\Model\SoftwareInternalLinkAccess_provider;
use App\Model\EnCourseMaster;

//use FFMpeg;

use App\Model\CrmAccounts;
use App\Model\User_user;
use App\Model\CrmLeads;
use App\Model\CrmOpportunities;
use App\Model\CrmCampaign;
use App\Model\CrmContacts;
use App\Model\CrmProducts;
use App\Model\EnProviderMailConfiguration;
use App\Model\EnCorporateMailConfiguration;
use App\Model\CrmAssignNotification;
use App\Model\ProjectMailConfiguration_provider;
use App\Model\EnCorporateUserAccess_corporate;
use App\Model\EnCourseEnrolled;
use App\Model\EnCourseConfiguration;
use App\Model\EnCourseContentConfiguration;
use App\Model\EnCourseModule;
use App\Model\EnCourseContent;
use App\Model\EnCourseContentDetails;
use App\Model\EnCourseContentVideo;
use App\Model\EnCourseContentAttFile;
use App\Model\EnCourseContentSlide;
use App\Model\EnCoursePackageModules;
use App\Model\EnCoursePackageModuleVideo;
use App\Model\EnCoursePackageModuleAttFile;
use App\Model\EnCoursePackageModuleSlide;
use App\Model\EnTraineeVideoWatchingHistory;
use App\Model\EnTraineeExam;
use App\Model\EnTraineeCourseRating;
use App\Model\EnTraineeTextContentReadingHistory;
use App\Model\EnClassroomUserAccess_classroom;
use App\Model\EnClassroomLiveClassSchedule_corporate;
use App\Model\SoftwareModules_provider;

class Helper {
	static $googleKey = 'AIzaSyCAtQRAg4TTTS0k7l31cyAQy0bBTHhYp4I';

    public static function mailConfig($configFrom='', $user_id=0, $configId=0) {
        $config = array();

        if(empty($config)) {
            $config = [
                'driver'        => 'sendmail',
                'host'          => 'eastwestbd.com',
                'SMTPAuth'      => true,
                'SMTPDebug'     => 2,
                'port'          => 465,
                'from'          => [
                'address'       => 'no_reply@eastwestbd.com',
                'name'          => 'EastWest'
                ],
                'encryption'    => 'starttls',
                'username'      => 'no_reply@eastwestbd.com',
                'password'      => '000EastWest000',
                'sendmail'      => '/usr/sbin/sendmail -bs',
                'pretend'       => false
           ];
        }

        Config::set('mail', $config);
        return $config;
    }

    public static function providerUserAccess($route, $module='', $isResource=1) {
        $user_id = Auth::guard('provider')->user()->id;

        if(!empty($module)) {
            switch($module) {
                case('tubingen'):
                    $module_id = 1;
                    break;
                case('admin'):
                    $module_id = 2;
                    break;
                default:
                    $module_id = 0;
                    break;
            }
            $module = SoftwareModules_provider::active()->find($module_id);
            $route = ($isResource==1) ? @$module->route_prefix.@$module->url_prefix.'.'.$route : @$module->route_prefix.$route;
        }

        $access = DB::table('software_access')->where('user_id', $user_id)->where('route', $route)->first();
        if(empty($access)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function corporateUserAccess($route, $module='', $isResource=1) {
        $user_id = Auth::corporate()->get()->id;

        if(!empty($module)) {
            switch($module) {
                case('training-manager'):
                    $module_id = 3;
                    break;
                case('admin'):
                    $module_id = 4;
                    break;
                default:
                    $module_id = 0;
                    break;
            }
            $module = SoftwareModules::active()->find($module_id);
            $route = ($isResource==1) ? @$module->route_prefix.@$module->url_prefix.'.'.$route : @$module->route_prefix.$route;
        }

        $access = DB::table('en_corporate_user_access_view')->where('user_id', $user_id)->where('route', $route)->first();
        if(empty($access)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function providerUserPageAccess($request) {
        $user_id = Auth::guard('provider')->id();
        $accessMenuId = $request->accessMenuId;
        $internalLink = SoftwareInternalLink_provider::active()->where('menu_id', $accessMenuId)->get();
        $accessLink = SoftwareInternalLinkAccess_provider::join('software_internal_link', 'software_internal_link_access.link_id', '=', 'software_internal_link.id')
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

    public static function corporateUserPageAccess($request) {
        $user_id = Auth::corporate()->get()->id;
        $accessMenuId = $request->accessMenuId;
        $internalLink = SoftwareInternalLink::active()->where('menu_id', $accessMenuId)->get();
        $accessLink = EnCorporateUserAccess_corporate::join('software_internal_link', 'en_corporate_user_access.link_id', '=', 'software_internal_link.id')
            ->where('software_internal_link.menu_id', $accessMenuId)
            ->where('en_corporate_user_access.user_id', $user_id)
            ->where('en_corporate_user_access.valid', 1)
            ->get()->pluck('link_id')->all();

        $access = array();
        foreach($internalLink as $internalLink) {
            $routeName = explode('.', $internalLink->route);
            $lastIndex = count($routeName)-1;
            $access[$routeName[$lastIndex]] = in_array($internalLink->id, $accessLink);
        }

        return (object) $access;
    }

    public static function classroomUserPageAccess($request) {
        $user_id = Auth::classroom()->get()->id;
        $accessMenuId = $request->accessMenuId;
        $internalLink = SoftwareInternalLink::active()->where('menu_id', $accessMenuId)->get();
        $accessLink = EnClassroomUserAccess_classroom::join('software_internal_link', 'en_classroom_user_access.link_id', '=', 'software_internal_link.id')
            ->where('software_internal_link.menu_id', $accessMenuId)
            ->where('en_classroom_user_access.user_id', $user_id)
            ->where('en_classroom_user_access.valid', 1)
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
        $admin_id = Auth::guard('softAdmin')->id();
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
        $output['messege'] = implode('',$validator->messages()->all('<i class="glyphicon glyphicon-ban-circle alert-icon validationErrIcon"></i><strong>:message</strong><br>'));
        $output['msgType'] = 'validationError';
        return $output;
    }


    //CRM................
    //Notification Data
    public static function assignNotifyData($data, $activity, $activity_id, $notify_reason) {
        $user_id = Auth::provider()->get()->id;
        $user_name = Auth::provider()->get()->name;

        $reasonInfo = self::assignNotifyReasonInfo($activity, $notify_reason);

        $notify_id = CrmAssignNotification::valid()->orderBy("id", "desc")->first()->id;
        $activityDetails = ucfirst($activity).": ".$data->subject;
        if(!empty($data->description)) {
            $activityDetails .= "\nDetails: ".self::subStrFunc($data->description, 80);
        }
        $activityDetails .= "\n".$reasonInfo->person_intro.": ".$user_name;

        switch ($notify_reason) {
            case 1:
                $notifYPerson_intro = 'Assigned by';
                break;
            case 2:
                $notifYPerson_intro = 'Assigned by';
                break;
            case 3:
                $notifYPerson_intro = 'Deleted by';
                break;
            case 4:
                $notifYPerson_intro = 'Accepted by';
                break;
            case 5:
                $notifYPerson_intro = 'Declined by';
                break;
            default:
                $notifYPerson_intro = "";
                break;
        }

        return array(
            "notify_id" => $notify_id,
            "title" => $reasonInfo->msg,
            "details" => $activityDetails,
            "type" => $activity,
            "assignBtn" => $reasonInfo->btn,
            "activity_id" => $activity_id,
            "activity_name" => $data->subject,
            "headerNotify" => $reasonInfo->msg.' - <a class="ajax-link" href="activities/task/'.$activity_id.'/'.$data->subject.'"><b>'.Helper::subStrFunc($data->subject, 30).'</b></a><div class="mt5">'.$notifYPerson_intro.' <a class="ajax-link" href="ownerProfile/'.$user_id.'/'.$user_name.'">'.$user_name.'</a></div>'
        );
    }

    public static function assignNotifyReasonInfo($activity, $notify_reason) {
        $assignBtn = false;
        switch ($notify_reason) {
            case 1:
                $notifYMsg = 'You have a new '.$activity;
                $assignBtn = true;
                $notifYPerson_intro = 'Assigned by';
                break;
            case 2:
                $notifYMsg = 'The '.$activity.' is assigned to another person';
                $notifYPerson_intro = 'Assigned by';
                break;
            case 3:
                $notifYMsg = 'Your '.$activity.' has been deleted';
                $notifYPerson_intro = 'Deleted by';
                break;
            case 4:
                $notifYMsg = 'Your '.$activity.' has been accepted';
                $notifYPerson_intro = 'Accepted by';
                break;
            case 5:
                $notifYMsg = 'Your '.$activity.' has been declined';
                $notifYPerson_intro = 'Declined by';
                break;
            default:
                $notifYMsg = "";
                $notifYPerson_intro = "";
                break;
        }
        return (object) array(
            "msg" => $notifYMsg,
            "person_intro" => $notifYPerson_intro,
            "btn" => $assignBtn
        );
    }

    //Back Office
    public static function crmBackOffice() {
        return CrmBackOfficeSetup::first();
    }

    //CRM Related Item Text
    public static function crmRelatedItemText($related_to, $related_item) {
        if($related_item>0) {
            switch($related_to) {
                case 1:
                    $related_item = CrmAccounts::valid()->find($related_item);
                    $related_item_text = @$related_item->account_name;
                    break;
                case 2:
                    $related_item = CrmLeads::valid()->find($related_item);
                    $name_title = DB::table('crm_name_title')->where('id', @$related_item->name_title)->first();
                    $name_title = (!empty($name_title)) ? @$name_title->name_title : '';
                    $related_item_text = trim($name_title.' '.@$related_item->first_name.' '.@$related_item->last_name.' '.@$related_item->surname);
                    break;
                case 3:
                    $related_item = CrmOpportunities::valid()->find($related_item);
                    $related_item_text = @$related_item->opportunity_name;
                    break;
                case 4:
                    $related_item = CrmCampaign::valid()->find($related_item);
                    $related_item_text = @$related_item->campaign_name;
                    break;
                case 5:
                    $related_item = CrmContacts::valid()->find($related_item);
                    $name_title = DB::table('crm_name_title')->where('id', @$related_item->name_title)->first();
                    $name_title = (!empty($name_title)) ? @$name_title->name_title : '';
                    $related_item_text = trim($name_title.' '.@$related_item->first_name.' '.@$related_item->last_name.' '.@$related_item->surname);
                    break;
                case 6:
                    $related_item = CrmProducts::valid()->find($related_item);
                    $related_item_text = @$related_item->product_name;
                    break;
                default:
                    $related_item_text = '';
            }
        } else {
            $related_item_text = '';
        }
        return $related_item_text;
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
	public static function getFileThumb($img, $path="") {
        $file_ext = explode(".", $img);
        if(count($file_ext)>1) {
            $file_ext = $file_ext[1];
            if($file_ext=='jpg' || $file_ext=='jpeg' || $file_ext=='png' || $file_ext=='gif') {
                if ($path == "") {
                    $thumb = url('/public/file_icon/png.png');
                } else {
                    $thumb = url($path.'/thumb/'.$img);
                }
            } else if($file_ext=='doc' || $file_ext=='docx') {
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
            } else if($file_ext=='mp4') {
                $thumb = url('/public/file_icon/mp4.jpg');
            } else {
                $thumb = url('/public/file_icon/zip.png'); //default
            }
            return $thumb;
        } else {
            $thumb = $img;
        }
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
		if($bytes > 0) {
			foreach($arBytes as $arItem) {
				if($bytes >= $arItem["VALUE"]) {
					$result = $bytes / $arItem["VALUE"];
					$result = strval(round($result, 2))." ".$arItem["UNIT"];
					break;
				}
			}
			return $result;
		} else {
			return 0;
		}

	}


	//For showing file size
	public static function getApiHashUrl($url, $allParameter) {
		$auth_key = Auth::provider()->get()->auth_key;
		$secret_key = Auth::provider()->get()->secret_key;
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

    public static function getDurationFormat($start_date, $end_date) {
        $date1=date_create($start_date);
        $date2=date_create($end_date);
        $diff=date_diff($date1,$date2);
        $day = (int) $diff->format("%a");
        $hour = (int) $diff->format("%H");
        $minute = (int) $diff->format("%I");
        $duration_text = "";
        $duration_value = 0;
        if($day==365){ $duration_text .= "1 year "; $day=0; }
        else if($day>365){ $dEx=explode(".", ($day/365)); $duration_text.=$dEx[0]." years "; $day=$day%365; }
        if($day==30){ $duration_text .= "1 month "; $day=0; }
        else if($day>30){ $dEx=explode(".", ($day/30)); $duration_text.=$dEx[0]." months "; $day=$day%30; }

        if($day==7){ $duration_text .= "1 week "; $duration_value += $day*24*60; }
        else if($day>0) { $duration_text .= ($day==1) ? "1 day " : $day." days "; $duration_value += $day*24*60; }
        if($hour>0){ $duration_text .= ($hour==1) ? "1 hour " : $hour." hours "; $duration_value += $hour*60; }
        if($minute>0){ $duration_text .= ($minute==1) ? "1 minute" : $minute." minutes"; $duration_value += $minute; }
		return $duration_text;
    }

    public static function minuteToDay($time) {
        if($time==0) {
            echo '0 minute';
        } else {
            $days = floor($time / (24*60));
            $hours = floor(($time - ($days*24*60)) / (60));
            $minutes = floor(($time - ($days*24*60)-($hours*60)));
            if(!empty($days)){echo $days.(($days>1)?' days ':' day ');}
            if(!empty($hours)){echo $hours.(($hours>1)?' hours ':' hour ');}
            if(!empty($minutes)){echo $minutes.(($minutes>1)?' minutes ':' minute ');}
        }
    }

    public static function secondToTimeFormat($time) {
        $min = $time%3600;
        $sec = $min%60;

        $hour = ($time>=3600) ? ($time-$min)/3600 : 0;
        $min = ($min>=60) ? ($min-$sec)/60 : 0;

        $hour = ($hour<=9) ? '0'.$hour : $hour;
        $min = ($min<=9) ? '0'.$min : $min;
        $sec = ($sec<=9) ? '0'.$sec : $sec;

        return $hour.':'.$min.':'.$sec;
    }

    public static function youtubeVideoTitle($video_id) {
        $json = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$video_id.'&key='.self::$googleKey.'&part=snippet');
        $ytdata = json_decode($json);
        if(!empty($ytdata->items)) {
            return $ytdata->items[0]->snippet->title;
        } else {
            return "";
        }
    }

    public static function youtubeVideoDuration($video_id) {
        $json = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$video_id.'&key='.self::$googleKey.'&part=contentDetails');
        $ytdata = json_decode($json);
        if(empty($ytdata->items)) {
            return 0;
        } else {
            $duration = $ytdata->items[0]->contentDetails->duration;
            $duration = new DateInterval($duration);
            $duration = ($duration->h*3600+$duration->i*60+$duration->s);
            return $duration;
        }
    }

    public static function createVideoThumb($attachm) {
        $attachm_exp = explode(".", $attachm);
        array_pop($attachm_exp);
        $thumbnail = implode(".", $attachm_exp);
        $videoPath = 'public/uploads/course/video_upload_local/'.$attachm;
        $thumbnail = 'public/uploads/course/video_upload_local/thumb/'.$thumbnail.'.jpg';

        /*$ffmpeg = FFMpeg\FFMpeg::create();
        $video = $ffmpeg->open(url($videoPath));
        $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))->save($thumbnail);*/

        //Local Linux
        //shell_exec("/home/admin-pc/bin/ffmpeg -i ".$videoPath." -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg ".$thumbnail." 2>&1");
    }

    public static function localVideoDuration($attachm) {
        $videoPath = 'public/uploads/course/video_upload_local/'.$attachm;

        /*$ffprobe = FFMpeg\FFProbe::create();
        $duration = $ffprobe->streams(url($videoPath))->videos()->first()->get('duration');*/

        //Local Linux
        //$duration = shell_exec('/home/admin-pc/bin/ffprobe -i '.$videoPath.' -show_entries format=duration -v quiet -of csv="p=0"');

        $duration = round((float)@$duration);
        return ($duration>0) ? $duration : 25;
    }

    public static function secondsToTime($seconds) {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        return $timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }

    public static function secondBetweenTwoDateTime($date1, $date2) {
        $date1=date_create($date1);
        $date2=date_create($date2);
        $diff=date_diff($date2,$date1);
        $day = (int) $diff->format("%a");
        $hour = (int) $diff->format("%H");
        $minute = (int) $diff->format("%I");
        $second = (int) $diff->format("%s");
        return (($minute + ($hour*60) + ($day*60*24))*60)+$second;
    }

    public static function courseDetails($course_id, $enroll_id) {
        $userId = @Auth::corporate()->get()->id;
        $totalVideos=0;
        $totalFiles=0;
        $totalModules=0;
        $totalExam=0;
		$courseEnrolled = EnCourseEnrolled::valid()->where("trainee_id", $userId)->where("course_id", $course_id)->find($enroll_id);
		$agreement_id = $courseEnrolled->corporate_course_agreement_id;

		$data['courseDetails'] = $course = DB::table('en_course_master')->where('valid', 1)->find($course_id);
		$data['courseConfiguration'] = $courseConfig = EnCourseConfiguration::valid()->where('course_id', $course_id)->where('agreement_id', $agreement_id)->first();

		//Text Read
        $textRead = EnTraineeTextContentReadingHistory::valid()
            ->where('enroll_id', $enroll_id)
            ->where('course_id', $course_id)
            ->where('trainee_id', $userId)
            ->get()->pluck('text_id')->all();
		//Watched Video
		$watchedVideos = EnTraineeVideoWatchingHistory::valid()
			->where('enroll_id', $enroll_id)
			->where('course_id', $course_id)
			->where('trainee_id', $userId)
			->where('duration_percentage', '>=', 80)
            ->get()->keyBy('video_id')->all();
		//All Videos
		$videos = EnCourseContentVideo::where('valid', 1)->where('course_id', $course_id)->get()->keyBy('id')->all();
		$contentVideos = array();

        if($course->course_mode==1) {
            //Course
			//Gived Exam
			$moduleExam = EnTraineeExam::valid()
				->where('enroll_id', $enroll_id)
				->where('package_id', 0)
				->where('course_id', $course_id)
				->where('exam_type', 2)
				->get()->keyBy('module_id')->all();

            $courseModules = EnCourseModule::join('en_course_content_configuration', function($join){
                    $join->on('en_course_module.id', '=', 'en_course_content_configuration.module_id')
                        ->on('en_course_content_configuration.module_id', '>', DB::raw(0))
                        ->on('en_course_content_configuration.content_details_id', '=', DB::raw(0));
                })
                ->select('en_course_module.*', 'en_course_content_configuration.exam', 'en_course_content_configuration.completion_required', 'en_course_content_configuration.sl_no as configSlNo')
                ->where('en_course_module.course_id', $course_id)
                ->where('en_course_content_configuration.agreement_id', $agreement_id)
                ->where('en_course_module.valid', 1)
                ->where('en_course_content_configuration.valid', 1)
                ->orderBy('configSlNo', 'asc')
                ->get()->keyBy('id')->all();
			$moduleSeqClearence = true;		//reqModSeq
			$videoSeqClearence = true;		//reqVideoSeq
			$moduleTextClearence = true;	//reqText
			$moduleVideoClearence = true;	//reqVideo
			$moduleExamClearence = true;	//reqModExam
            foreach($courseModules as $module) {
				$curModuleClearence = true;
				$contentClearence = true;
				$textClearence = true;
				$videoClearence = true;
				//Contents
                $contents = EnCourseContentDetails::join('en_course_content_configuration', 'en_course_content_details.id', '=', 'en_course_content_configuration.content_details_id')
                    ->select('en_course_content_details.*', 'en_course_content_configuration.completion_required', DB::raw('IF(ISNULL(en_course_content_configuration.sl_no), en_course_content_details.sl_no, en_course_content_configuration.sl_no) as configSlNo'))
                    ->where('en_course_content_details.module_id', $module->id)
                    ->where('en_course_content_configuration.agreement_id', $agreement_id)
                    ->where('en_course_content_details.valid', 1)
                    ->where('en_course_content_configuration.valid', 1)
                    ->orderBy('configSlNo', 'asc')
                    ->get()->keyBy('id')->all();


				foreach($contents as $content) {
                    /*echo "<pre>";
                    print_r($content->content_id);*/
					switch($content->type) {
						case 1:
							// Text...
							if(in_array($content->content_id, $textRead)) { $content->textRead = true; }
							else {
								$content->textRead = false;
								if($content->completion_required==1) { $contentClearence = false; $textClearence = false; }
							}
							if($moduleTextClearence && !$textClearence) { $moduleTextClearence = false; }
							break;
						case 2:
							// Video...
							$videos[$content->content_id]->module_id = $content->module_id;
							$contentVideos[] = $videos[$content->content_id];
							$content->videoInfo = $videos[$content->content_id];
							if(array_key_exists($content->content_id, $watchedVideos)) { $content->videoWatched = true; }
							else {
								$content->videoWatched = false;
								if($content->completion_required==1) { $contentClearence = false; $videoClearence = false; }
							}
							if($moduleVideoClearence && !$videoClearence) { $moduleVideoClearence = false; }
							//video_sequence Check
							$content->reqVideoSeq = false;
							if(!$videoSeqClearence) {
								$content->reqVideoSeq = true;
							} else if($courseConfig->video_sequence==1 && !$videoClearence) {
								$videoSeqClearence = false;
							}
							$totalVideos++;
							break;
						case 4:
							// File...
							$totalFiles++;
							break;
					}
					//module_sequence Check
					$content->reqModSeq = false;
					if(!$moduleSeqClearence) {
						$content->reqModSeq = true;
					} else if($curModuleClearence && $courseConfig->module_sequence==1 && !$contentClearence) {
						$curModuleClearence = false;
					}
				}
				$module->contents = $contents;

                /*echo "<pre>";
                print_r($textRead);
                die();*/

				//Module Exam
				if($module->exam==1) {
					if(array_key_exists($module->id,$moduleExam)) {
						$module->examInfo = $moduleExam[$module->id];
						$module->exam_id = $moduleExam[$module->id]->id;
					} else {
						$module->exam_id = 0;
						if($moduleExamClearence && $module->completion_required==1) { $moduleExamClearence = false; }
					}
					//module_sequence Check (For Exam)
					$module->reqModSeq = false;
					if(!$moduleSeqClearence) {
						$module->reqModSeq = true;
					} else if($curModuleClearence && $courseConfig->module_sequence==1 && !$moduleExamClearence) {
						$curModuleClearence = false;
					}
					$module->reqText = ($textClearence) ? false : true;
					$module->reqVideo = ($videoClearence) ? false : true;
					$totalExam++;
				}

				if(!$curModuleClearence) { $moduleSeqClearence = false; }
            }
			$data['courseModules'] = $courseModules;
			$data['totalModules'] = count($courseModules);
            $data['totalVideos'] = $totalVideos;
            $data['totalFiles'] = $totalFiles;

			//Final Exam
			$data['courseExam'] = EnCourseContentConfiguration::valid()->where('course_id', $course_id)->where('agreement_id', $agreement_id)->where('module_id', 0)->where('content_details_id', 0)->first();
			if($data['courseExam']->exam==1) {
				$courseExam = EnTraineeExam::valid()
					->where('enroll_id', $enroll_id)
					->where('package_id', 0)
					->where('course_id', $course_id)
					->where('exam_type', 1)
					->first();
				$data['courseDetails']->exam_id = (!empty($courseExam)) ? $courseExam->id : 0;
				$data['courseDetails']->examInfo = (!empty($courseExam)) ? $courseExam : [];
				if(empty($courseExam)) {
					$data['courseDetails']->reqModSeq = ($moduleSeqClearence) ? false : true;
					$data['courseDetails']->reqText = ($moduleTextClearence) ? false : true;
					$data['courseDetails']->reqVideo = ($moduleVideoClearence) ? false : true;
					$data['courseDetails']->reqModExam = ($moduleExamClearence) ? false : true;
				}
				$totalExam++;
			}
			$data['totalExam'] = $totalExam;

            /*foreach ($courseModules as $courseModule) {
                $courseContent = EnCourseContent::where('module_id', $courseModule->id)->where('course_id', $course_id)->first();
                $courseModule->courseContentText = $courseContent;

                $courseModule->textRead = (in_array($courseModule->id, $textReadModules)) ? true : false;
                $exam = EnTraineeExam::valid()
                    ->where('enroll_id', $enroll_id)
                    ->where('package_id', 0)
                    ->where('course_id', $course_id)
                    ->where('module_id', $courseModule->id)
                    ->where('exam_type', 2)
                    ->first();
                $courseModule->exam_id = (!empty($exam)) ? $exam->id : 0;
                if(!empty($exam)) { $courseModule->exam = $exam; }

                $contentVideos = EnCourseContentVideo::join('en_course_content', 'en_course_content_video.course_content_id', '=', 'en_course_content.id')
                    ->select('en_course_content_video.*')
                    ->where('en_course_content.module_id', $courseModule->id)
                    ->where('en_course_content.valid', 1)
                    ->where('en_course_content_video.valid', 1)
                    ->orderBy('sl_no', 'asc')
                    ->get();
                $contentVideos = self::videoInfoList($contentVideos, $course_id, $enroll_id, $userId);
                $courseModule->contentVideos = $contentVideos;
                if($examCompleted) {
                    if(!empty($contentVideos)) {
                        if(empty($videos)) {
                            $videos=$contentVideos->toArray();
                        } else {
                            $videos = array_merge($videos, $contentVideos->toArray());
                        }
                    }
                    $courseModule->contentReqExam = 0;
                    if(empty($exam)) { $examCompleted=false; }
                } else {
                    $courseModule->contentReqExam = 1;
                }
                $totalVideos =  $totalVideos + count($courseModule->contentVideos);

                if(isset($courseContent) && @$courseContent->file_att_status==1) {
                    $courseModule->contentAttFiles = EnCourseContentAttFile::join('en_course_content', 'en_course_content_att_file.course_content_id', '=', 'en_course_content.id')
                        ->select('en_course_content_att_file.*')
                        ->where('en_course_content.module_id', $courseModule->id)
                        ->where('en_course_content.valid', 1)
                        ->where('en_course_content_att_file.valid', 1)
                        ->orderBy('id', 'desc')
                        ->get();
                    $totalFiles =  $totalFiles + count($courseModule->contentAttFiles);
                } else {
                    $courseModule->contentAttFiles = [];
                }

                //Slide
                if(isset($courseContent) && @$courseContent->slide_show_status==1) {
                    $courseModule->contentSlides = EnCourseContentSlide::join('en_course_content', 'en_course_content_slide.course_content_id', '=', 'en_course_content.id')
                        ->select('en_course_content_slide.*')
                        ->where('en_course_content.module_id', $courseModule->id)
                        ->where('en_course_content.valid', 1)
                        ->where('en_course_content_slide.valid', 1)
                        ->orderBy('id', 'desc')
                        ->get();
                } else {
                    $courseModule->contentSlides = [];
                }

            }
            $data['courseDetails']->contentReqExam = ($examCompleted) ? 0 : 1;

            $data['courseModules'] = $courseModules;
            $data['totalModules'] = count($courseModules);
            $data['totalVideos'] = $totalVideos;
            $data['totalFiles'] = $totalFiles;*/
        } else {
            $totalModules = 0;
            //Package
            $packageCourses = EnCoursePackageModules::join("en_course_module", "en_course_package_modules.course_module_id", "=", "en_course_module.id")
                ->join('en_course_master', 'en_course_module.course_id', '=', 'en_course_master.id')
                ->select("en_course_module.*", "en_course_master.course_name", "en_course_package_modules.sl_no as pac_sl_no")
                ->where("en_course_package_modules.package_id", $course_id)
                ->where("en_course_package_modules.valid", 1)
                ->groupBy("en_course_module.course_id")
                ->orderBy('pac_sl_no', 'asc')
                ->get();

            foreach ($packageCourses as $packageCourse) {
                $courseExam = EnTraineeExam::valid()
                    ->where('enroll_id', $enroll_id)
                    ->where('package_id', $course_id)
                    ->where('course_id', $packageCourse->course_id)
                    ->where('exam_type', 1)
                    ->first();
                $packageCourse->exam_id = (!empty($courseExam)) ? $courseExam->id : 0;
                if(!empty($courseExam)) { $packageCourse->exam = $courseExam; }

                $courModule = EnCoursePackageModules::join("en_course_module", "en_course_package_modules.course_module_id", "=", "en_course_module.id")
                    ->select("en_course_package_modules.*", "en_course_module.module_name")
                    ->where("en_course_package_modules.package_id", $course_id)
                    ->where("en_course_module.course_id", $packageCourse->course_id)
                    ->where("en_course_package_modules.valid", 1)
                    ->orderBy('sl_no', 'asc')
                    ->get();

                foreach ($courModule as $courseModule) {
                    $courseModule->textRead = (in_array($courseModule->id, $textReadModules)) ? true : false;
                    $exam = EnTraineeExam::valid()
                        ->where('enroll_id', $enroll_id)
                        ->where('package_id', $course_id)
                        ->where('course_id', $packageCourse->course_id)
                        ->where('package_module_id', $courseModule->id)
                        ->where('exam_type', 2)
                        ->first();
                    $courseModule->exam_id = (!empty($exam)) ? $exam->id : 0;
                    if(!empty($exam)) { $courseModule->exam = $exam; }

                    $contentVideos = EnCoursePackageModuleVideo::where('valid', 1)->where('package_module_id', $courseModule->id)->orderBy('sl_no', 'asc')->get();
                    $contentVideos = self::videoInfoList($contentVideos, $course_id, $enroll_id, $userId);
                    $courseModule->contentVideos = $contentVideos;
                    if($examCompleted) {
                        if(!empty($contentVideos)) {
                            if(empty($videos)) {
                                $videos=$contentVideos->toArray();
                            } else {
                                $videos = array_merge($videos, $contentVideos->toArray());
                            }
                        }
                        $courseModule->contentReqExam = 0;
                        if(empty($exam)) { $examCompleted=false; }
                    } else {
                        $courseModule->contentReqExam = 1;
                    }

                    $totalVideos =  $totalVideos + count($courseModule->contentVideos);
                    $courseModule->contentAttFiles = ($courseModule->file_att_status==1) ? EnCoursePackageModuleAttFile::where('valid', 1)->where('package_module_id', $courseModule->id)->orderBy('id', 'desc')->get() : [];
                    $totalFiles =  $totalFiles + count($courseModule->contentAttFiles);

                    //Slide
                    $courseModule->contentSlides = ($courseModule->slide_show_status==1) ? EnCoursePackageModuleSlide::where('valid', 1)->where('package_module_id', $courseModule->id)->orderBy('id', 'desc')->get() : [];
                }
                if($examCompleted) {
                    $packageCourse->contentReqExam = 0;
                    if(empty($courseExam)) { $examCompleted=false; }
                } else {
                    $packageCourse->contentReqExam = 1;
                }
                $packageCourse->courModule = $courModule;
                $totalModules =  $totalModules + count($courModule);
            }
            $data['packageCourses'] = $packageCourses;
            $data['totalModules'] = $totalModules;
            $data['totalVideos'] = $totalVideos;
            $data['totalFiles'] = $totalFiles;
        }
        $data['videos'] = $contentVideos;

        $data['course_id'] = $course_id;
        $data['enroll_id'] = $enroll_id;
        return $data;
    }

	public static function courseStatusUpdate($enroll) {
		$reqContent = EnCourseContentConfiguration::where('valid', 1)->where('course_id', $enroll->course_id)->where('agreement_id', $enroll->corporate_course_agreement_id)->where('completion_required', 1)->get();
		$contentDetails = EnCourseContentDetails::where('valid', 1)->where('course_id', $enroll->course_id)->where('type', '<', 3)->get()->keyBy('id')->all();
		$textRead = EnTraineeTextContentReadingHistory::valid()->where('enroll_id', $enroll->id)->where('trainee_id', $enroll->trainee_id)->get()->pluck('text_id')->all();
		$watchedVideos = EnTraineeVideoWatchingHistory::valid()->where('enroll_id', $enroll->id)->where('trainee_id', $enroll->trainee_id)->where('duration_percentage', '>=', 80)->get()->pluck('video_id')->all();
		$moduleExam = EnTraineeExam::valid()->where('enroll_id', $enroll->id)->where('package_id', 0)->where('course_id', $enroll->course_id)->where('exam_type', 2)->get()->pluck('module_id')->all();
		$courseExam = EnTraineeExam::valid()->where('enroll_id', $enroll->id)->where('package_id', 0)->where('course_id', $enroll->course_id)->where('exam_type', 1)->first();

		$courseComplete=true;
		foreach($reqContent as $reqContent) {
			if($reqContent->module_id==0 && $reqContent->exam==1) {
				//Check Final Exam
				if(empty($courseExam)) { $courseComplete=false; break; }
			} else if($reqContent->module_id>0 && $reqContent->exam==1) {
				//Check Module Exam
				if(!in_array($reqContent->module_id, $moduleExam)) { $courseComplete=false; break; }
			} else if($reqContent->content_details_id>0) {
				if(@$contentDetails[$reqContent->content_details_id]->type==1) {
					//Check Text
					if(!in_array($contentDetails[$reqContent->content_details_id]->content_id, $textRead)) { $courseComplete=false; break; }
				} else if(@$contentDetails[$reqContent->content_details_id]->type==2) {
					//Check Video
					if(!in_array($contentDetails[$reqContent->content_details_id]->content_id, $watchedVideos)) { $courseComplete=false; break; }
				} else {
					$courseComplete=false; break;
				}
			}
		}
		if($courseComplete) {
			$enroll->update(["course_status"=>2, "course_completion_date"=>date('Y-m-d')]);
		} else if($enroll->course_status==0) {
			$enroll->update(["course_status"=>1]);
		}
    }

    public static function courseRatings($courses) {
        foreach ($courses as $course) {
            //Rating
            $ratingPerson = EnTraineeCourseRating::where('valid', 1)->where('course_id', $course->id)->count();
            $totalRating = EnTraineeCourseRating::where('valid', 1)->where('course_id', $course->id)->sum('rating');
            $course->rating = ($ratingPerson>0)?($totalRating/($ratingPerson*5))*5:0;
            $course->ratingPercent = ($ratingPerson>0)?($totalRating/($ratingPerson*5))*100:0;
        }
        return $courses;
    }

    public static function videoInfoList($videos, $course_id, $enroll_id, $userId) {
        if(!empty($videos)) {
            $videoPermission = true;
            foreach($videos as $video) {
                if(empty($video->youtube_video_id)) {
                    $attachm_exp = explode(".", $video->attachment);
                    array_pop($attachm_exp);
                    $video->videoThumb = implode(".", $attachm_exp).'.jpg';
                }
                $video->videoTitle = $video->video_title;
                $video->showRequire = ($video->video_duration>0) ? $video->video_duration*(80/100) : 0;

                if(!empty($userId)) {
                    //Duration
                    $duration = EnTraineeVideoWatchingHistory::valid()
                        ->where('enroll_id', $enroll_id)
                        ->where('course_id', $course_id)
                        ->where('video_id', $video->id)
                        ->where('trainee_id', $userId)
                        ->first();
                    $video->showTime = (!empty($duration)) ? $duration->duration : 0;

                    if($videoPermission) {
                        $video->videoReqShow = 0;
                        if($video->showRequire>$video->showTime) { $videoPermission=false; }
                    } else {
                        $video->videoReqShow = 1;
                    }
                }
            }
        }
        return $videos;
    }

    public static function popularCourses($courseId) {
        $footerData['popularCourses'] = EnCourseMaster::where('valid', 1)->orderBy('id', 'desc')->limit(2)->get();
        $footerData['courses'] = EnCourseMaster::where('valid', 1)->orderBy('id', 'desc')->limit(3)->get();

        if (Auth::corporate()->check()){
            $traineeId = Auth::corporate()->get()->id;
            $validEnrolledCoursesId = EnCourseEnrolled::where('en_course_enrolled.trainee_id', $traineeId)
                ->select('en_course_enrolled.*')
                ->where('en_course_enrolled.course_effective_end_date', '>', $today)
                ->groupBy('en_course_enrolled.course_id')
                ->where('en_course_enrolled.valid', 1)
                ->get();
            $footerData['enrolledCourses'] = array_pluck($validEnrolledCoursesId, 'course_id');
        }

        return $footerData;
    }


    public static function courseContentDetails($agreement_id=0, $account_id=0, $course_id, $type, $data_type){
        if ($data_type != 0) {
           $courseContentDetails = EnCourseContentConfiguration::join('en_course_content_details', 'en_course_content_details.id', '=', 'en_course_content_configuration.content_details_id')
                ->where(function($query) use ($data_type){
                    $query->where('en_course_content_details.type', $data_type);
                })
                ->where('en_course_content_configuration.agreement_id', $agreement_id)
                ->where('en_course_content_configuration.course_id', $course_id)
                ->where('en_course_content_configuration.type', $type)
                ->where('en_course_content_configuration.account_id', $account_id)
                ->where('en_course_content_configuration.valid', 1)->count();
        } else {
            $courseContentDetails = EnCourseContentConfiguration::where('en_course_content_configuration.agreement_id', $agreement_id)
                ->where('en_course_content_configuration.course_id', $course_id)
                ->where('en_course_content_configuration.type', $type)
                ->where('en_course_content_configuration.account_id', $account_id)
                ->where('en_course_content_configuration.module_id', '!=', 0)
                ->where('en_course_content_configuration.content_details_id', 0)
                ->where('en_course_content_configuration.valid', 1)->count();
        }
        return $courseContentDetails;
    }

    public static function getUserImageThumb($image) {
        if(!empty($image)) {
            $src = url('public/uploads/user_images/thumb/'.$image);
        } else {
            $src = url('public/web/images/profile_picture.png');
        }
        return $src;
    }

    public static function getUserImage($image) {
        if(!empty($image)) {
            $src = url('public/uploads/user_images/'.$image);
        } else {
            $src = url('public/web/images/profile_picture.png');
        }
        return $src;
    }

    public static function dateTime($dateTime){
        $date = new DateTime($dateTime);
        return $regDate = $date->format('d-m-Y g:i A');
    }

    public static function dateTimeYMD($dateTime){
        $date = new DateTime($dateTime);
        return $regDate = $date->format('Y-m-d g:i A');
    }

    public static function date($date){
        $date=date_create($date);
        return $date = date_format($date,"d-m-Y");
    }

    public static function dateMdY($date){
        $date=date_create($date);
        return $date = date_format($date,"M d, Y");
    }

    public static function dateYMD($date){
        $date=date_create($date);
        return $date = date_format($date,"Y-m-d");
    }

    public static function timeHis($time){    
        $time = DateTime::createFromFormat('g:i A', $time);
        $time = $time->format('H:i:s');
    }

    public static function timeGia2($time){
        return $time = date("g:i a", strtotime($time));
    }

    public static function timeGia($time){    
        $time = DateTime::createFromFormat('H:i:s', $time);
        $time = $time->format('g:i A');
    }

    public static function lastCourseId()
    {
        $data = EnCourseMaster::valid()
                              ->orderBy('id','desc')
                              ->first()->id;
        return $data;
    }

    public static function monthWiseDay() {
        $list=[];
        $month = date('m');
        $year = date('Y');

        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);          
            if (date('m', $time)==$month)       
            $list[]= date('d', $time);
        }
        return (object)$list;
    }


    public static function numberShow($length){
        $number = [];
        for ($i=1; $i <= $length; $i++) { 
            $number[] +=$i;
        }
        return (object)$number;
    }

     //GET CURRENT URL
     public static function getCurrentUrl() {
        $url = $_SERVER['REQUEST_URI'];
        $url_array = explode("/", $url);
        // return $current_module = $url_array[3]; // For Local
        return $current_module = $url_array[2]; // For Online
    }

    //START GET TRAINEE REPORT
    public static function getTraineeReport($trainee_id,$enroll_id, $module_id){
        $traineeReport =  EnTraineeExam::where('trainee_id', $trainee_id)
            ->where('module_id', $module_id)
            ->where('enroll_id', $enroll_id)
            ->where('valid', 1)
            ->first();
        return $traineeReport;
    }
    //END GET TRAINEE REPORT

    public static function token()
    {
        return "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6InRFMmFZYUFMUzdxUkRGMEpORmNEbXciLCJleHAiOjE1NzA2MDE0MjYsImlhdCI6MTU2OTk5NjYyOX0.76x1QY_v2gBZUzt5e2OYHUsdKn9scv_o1aBWpkNCuNs";
    }


    //FOR ZOOM INTEGRATION (CREATE / UPDATE)
    public static function zoomIntegrationFunction($curl_url, $curl_method, $postFields, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $curl_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $curl_method,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer".$token,
            "content-type: application/json"
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $output['messege'] = $err;
            $output['msgType'] = 'error';
            $output['msgStatus'] = 0;
        } else {
            $output['info'] = json_decode($response);
            $output['msgStatus'] = 1;
        }

        return $output;
    }
    //END FOR ZOOM INTEGRATION (CREATE / UPDATE)


    //TIME CALCULATION
    public static function time($time) {
        $get_hour = substr($time, 0, 2);
        $get_time_format = substr($time, -2);
        
        $get_min = substr($time, -5);
        $real_min = substr($get_min, 0, 2);

        $real_hour = '';
        switch(TRUE)
        {
           case ($get_hour=='12'&&$get_time_format=='AM'):
           $real_hour = "18";
           break;

           case ($get_hour=='1:'&&$get_time_format=='AM'):
           $real_hour = "19";
           break;

           case ($get_hour=='2:'&&$get_time_format=='AM'):
           $real_hour = "20";
           break;

           case ($get_hour=='3:'&&$get_time_format=='AM'):
           $real_hour = "21";
           break;

           case ($get_hour=='4:'&&$get_time_format=='AM'):
           $real_hour = "22";
           break;

           case ($get_hour=='5:'&&$get_time_format=='AM'):
           $real_hour = "23";
           break;

           case ($get_hour=='6:'&&$get_time_format=='AM'):
           $real_hour = "00";
           break;

           case ($get_hour=='7:'&&$get_time_format=='AM'):
           $real_hour = "01";
           break;

           case ($get_hour=='8:'&&$get_time_format=='AM'):
           $real_hour = "02";
           break;

           case ($get_hour=='9:'&&$get_time_format=='AM'):
           $real_hour = "03";
           break;

           case ($get_hour=='10'&&$get_time_format=='AM'):
           $real_hour = "04";
           break;

           case ($get_hour=='11'&&$get_time_format=='AM'):
           $real_hour = "05";
           break;

           case ($get_hour=='12'&&$get_time_format=='AM'):
           $real_hour = "06";
           break;

           case ($get_hour=='1:'&&$get_time_format=='PM'):
           $real_hour = "07";
           break;

           case ($get_hour=='2:'&&$get_time_format=='PM'):
           $real_hour = "08";
           break;

           case ($get_hour=='3:'&&$get_time_format=='PM'):
           $real_hour = "09";
           break;

           case ($get_hour=='4:'&&$get_time_format=='PM'):
           $real_hour = "10";
           break;

           case ($get_hour=='5:'&&$get_time_format=='PM'):
           $real_hour = "11";
           break;

           case ($get_hour=='6:'&&$get_time_format=='PM'):
           $real_hour = "12";
           break;

           case ($get_hour=='7:'&&$get_time_format=='PM'):
           $real_hour = "13";
           break;

           case ($get_hour=='8:'&&$get_time_format=='PM'):
           $real_hour = "14";
           break;

           case ($get_hour=='9:'&&$get_time_format=='PM'):
           $real_hour = "15";
           break;

           case ($get_hour=='10'&&$get_time_format=='PM'):
           $real_hour = "16";
           break;

           case ($get_hour=='11'&&$get_time_format=='PM'):
           $real_hour = "17";
           break;

           default:
           $real_hour = "00";
           break;

        }

        return $time = $real_hour.':'.$real_min.':00';
    }

    //ZOOM DATA GET/DELETE
    public static function zoomGetDelete($curl_method, $meeting_id) {
        $token = self::token();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zoom.us/v2/meetings/". $meeting_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $curl_method,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer".$token,
                "content-type: application/json"
            ),
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $output['messege'] = $err;
            $output['msgType'] = 'error';
            $output['msgStatus'] = 0;
        } else {
            $output['info'] = json_decode($response);
            $output['msgStatus'] = 11;
            $output['messege'] = '';
        }

        return $output;
    }


    //START NEXT DATE
    public static function nextDate($date, $day) {
        $date = new DateTime($date);
        $date->add(new DateInterval('P'.$day.'D'));
        $nextDate = $date->format('Y-m-d');

        return $nextDate;
    }
    //END NEXT DATE

    //START DATE DIFFERENCE
    public static function dateDiff($startDate, $endDate) {
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $dateDiff = $startDate->diff($endDate);

        return $days = $dateDiff->format('%a');
    }
    //END DATE DIFFERENCE


    //DAYS DATES
    public static function nextAllDays($startDate, $endDate, $every){
        $days = self::dateDiff($startDate, $endDate);
        $newDates[] = $startDate;
        $day = $every;
        $totalDays = explode('.', $days/$every);
        $totalDays = $totalDays[0];

        for($i=0; $i<$totalDays; $i++) {
            $Ndate = self::nextDate($startDate, $day);
            $startDate = $Ndate;
            $newDates[] = $Ndate;
        }

        return $newDates;
    }
    //END DAYS DATES

    //CLASSROOM CLASS SCHEDULES
    public static function classroomClassSchedules($assign_class_id){
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $schedules = EnClassroomLiveClassSchedule_corporate::valid()->where('assign_class_id', $assign_class_id)->select('start_date', 'end_time')->get();

        for($i=0; $i<=count($schedules)-1; $i++) {
            if ($current_date<=$schedules[$i]) {
                if (time() < strtotime($schedules[$i]->end_time)) {
                    return $schedul_date =  $schedules[$i]->start_date;
                    break;
                } else {
                    if ($i==count($schedules)-1) {
                        return $schedul_date =  $schedules[$i]->start_date;
                    } else {
                        return $schedul_date =  $schedules[$i+1]->start_date;
                    }
                    break;
                }
            }
        }
        
    }
    //END CLASSROOM CLASS SCHEDULES


    //CROP IMAGE SAVE 
    public static function cropImageManagement($previous_image_name,$new_image_name,$path,$image_data){

         // $previous_image_name=$previous_image_name;
        File::delete(public_path().$path.$previous_image_name);
        File::delete(public_path().$path.'/thumb/'.$previous_image_name);


        $image_data = $image_data; //$_POST['gallery_thumb'];
        list($type, $image_data) = explode(';', $image_data);
        list(, $image_data)      = explode(',', $image_data);

        $image_data = base64_decode($image_data);
        // $imageName = time().'.png';
        $imageName= $new_image_name;
      

       
        file_put_contents(public_path().$path.$imageName, $image_data);
        //create instance
        $img = Image::make(public_path().$path.$imageName);
        //resize image
        $img->resize(80, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path().$path.'/thumb/'.$imageName); //save the same file as


    }

 //CROP IMAGE SAVE 
    public static function ThumbcropWithRealImage($image_name,$path,$crop_image_data,$real_image_data){

        File::delete(public_path().$path.$image_name);
        File::delete(public_path().$path.'/thumb/'.$image_name);


        $real_image_data = $real_image_data; //$_POST['gallery_thumb'];
        list($type, $real_image_data) = explode(';', $real_image_data);
        list(, $real_image_data)      = explode(',', $real_image_data);

        $real_image_data = base64_decode($real_image_data);
        // $imageName = time().'.png';
        $imageName=$image_name;

       

        file_put_contents(public_path().$path.$imageName, $real_image_data);
        //create instance
        $img = Image::make(public_path().$path.$imageName);

        //Crop Image Save
        $crop_image_data = $crop_image_data; //$_POST['gallery_thumb'];
        // dd($crop_image_data);
        list($type, $crop_image_data) = explode(';', $crop_image_data);
        list(, $crop_image_data)      = explode(',', $crop_image_data);

        $crop_image_data = base64_decode($crop_image_data);
        // $imageName = time().'.png';
        $imageName=$image_name;

       

        file_put_contents(public_path().$path.'/thumb/'.$imageName, $crop_image_data);
        //create instance
        $img = Image::make(public_path().$path.'/thumb/'.$imageName);


        //resize image
        // $img->resize(80, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        // $img->save($path.'/thumb/'.$imageName); //save the same file as


    }





//vedio exits helper 1=vimeo 2=Youtube
    public static function check_remote_video_exists($video_id,$media_id) {
    
    //dd($media_id);
        if ($media_id==1){
            $video_url='https://vimeo.com/'.$video_id;
            $headers = @get_headers($video_url);

            return (strpos($headers[0], '200') > 0) ? true : false;
        } else if ($media_id==2){

                $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $video_id);
                //return $headers;
                
               
                if(is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false){
                        return true;        
                    } else {

                        return false; 
                    }





         }
 
    }  

        
    //END CROP IMAGE SAVE 
    
    
    // get employee name
    public static function getEmployeeName($id){
        return DB::table('en_provider_user')->where('valid',1)->where('id',$id)->first()->name;
    }

    public static function getCountryName($country_id)
    {
        return DB::table('en_country')->find($country_id)->name;
    }
    
}
