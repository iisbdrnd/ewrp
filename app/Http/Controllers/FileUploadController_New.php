<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;
use File;
use Image;
use Thumbnail;
use Carbon;

use Intervention\Image\ImageManager;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FileUploadController extends Controller
{
    // Only For Hacking Security (ignore ext in js)
    public $valid_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'csv', 'xls', 'xlsx', 'zip', 'rar', 'tar', 'txt', 'mng', 'avchd', 'wmv', 'avi', 'mov', 'mp4', 'mpg', 'html');
    public $valid_path = array();

    function  __construct() {

 $this->valid_path['public/uploads/logo'] = array('project_info.logo');
            $this->valid_path['public/uploads/about'] = array('about.image');
            $this->valid_path['public/uploads/social_logo'] = array('social_link.social_logo');
            $this->valid_path['public/uploads/view_finder'] = array('viewfinder.main_image');
            $this->valid_path['public/uploads/view_finder/thumbnail'] = array('viewfinder.thumbnail');
            $this->valid_path['public/uploads/intro_logo'] = array('general.intro_logo');
            $this->valid_path['public/uploads/menu_logo'] = array('general.menu_logo');
            // provider photography
            $this->valid_path['public/uploads/photography'] = array('photography.gallery_thumb');
            $this->valid_path['public/uploads/gallery'] = array('gallery.image');
            $this->valid_path['public/uploads/ashram'] = array('ashram.image');
            $this->valid_path['public/uploads/ashramGallery'] = array('ashramGallery.image');

            $this->valid_path['public/uploads/social_logo'] = array('social_link.social_logo');
            $this->valid_path['public/uploads/commercial'] = array('commercial.video_thumb');
            $this->valid_path['public/uploads/narrative'] = array('narrative.video_thumb');

    //     if((Auth::provider()->check())) {
    //         if(Helper::providerUserAccess('provider.tManager.provider.training-manager.corporateAccount.create')) {
    //             $this->valid_path['public/uploads/corporateAccount'] = array('en_corporate_account.image');
    //         }

    //         if(Helper::providerUserAccess('provider.tManager.provider.training-manager.course.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.course.edit')) {
    //             $this->valid_path['public/uploads/course_thumb'] = array('en_course.course_thumb');
    //         }

    //         if(Helper::providerUserAccess('provider.tManager.provider.training-manager.courseContent.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.courseContent.edit') || Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.edit')) {
    //             $this->valid_path['public/uploads/course/video_upload_local'] = array('en_course_content_video.attachment', 'en_archive_course_video.attachment');
    //         }

    //         if(Helper::providerUserAccess('provider.tManager.provider.training-manager.courseContent.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.courseContent.edit') || Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.edit')) {
    //             $this->valid_path['public/uploads/course/course_cont_file_attachment'] = array('en_course_content_att_file.attachment', 'en_archive_course_att_file.attachment');
    //         }
    //         if(Helper::providerUserAccess('provider.tManager.provider.training-manager.courseCategory.create')) {
    //             $this->valid_path['public/uploads/category_image'] = array('en_course_category.category_image');
    //         }
    //         //START FOR ARCHIVE COURSES
    //         // if(Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.edit')) {
    //         //     $this->valid_path['public/uploads/archiveCourse/video_upload_local'] = array('en_course_content_video.attachment');
    //         // }
    //         //
    //         // if(Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.create') || Helper::providerUserAccess('provider.tManager.provider.training-manager.archiveCourse.edit')) {
    //         //     $this->valid_path['public/uploads/archiveCourse/course_cont_file_attachment'] = array('en_course_content_att_file.attachment');
    //         // }

    //         //END FOR ARCHIVE COURSES
            
    //     }

    //     if((Auth::admin()->check())) {
    //         $this->valid_path['public/uploads/logo'] = array('project_info.logo');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/user_images'] = array('en_users.image');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/user_images'] = array('en_trainee_user_info.image');
    //     }
        
    //     // if((Auth::corporate()->check())) {
    //     //     $this->valid_path['public/uploads/user_images'] = array('en_corporate_user_info.image');
    //     // }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/student_photo'] = array('en_student_registration_bibm.participant_photo');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/class_room_attachments'] = array('en_classroom_class_attachments.attachment_name');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/instructor_signature'] = array('en_teacher_info.signature');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/course/video_upload_local'] = array('en_course_content_video.attachment');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/course_thumb'] = array('en_course_master.course_thumb');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/course/course_cont_file_attachment'] = array('en_course_content_att_file.attachment');
    //     }

    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/certificate_design_upload'] = array('en_corporate_certificate_configuration.attachment');
    //     }
    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/corporate_certificate_image'] = array('en_corporate_certificate_image.image');
    //     }
    //     if((Auth::corporate()->check())) {
    //         $this->valid_path['public/uploads/class_room'] = array('en_classroom_class.class_thumb');
    //     }



    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/provider_user_images'] = array('en_provider_user_info.image');
    //     }

    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/webinar_instructor'] = array('en_webinar.image');
    //     }

    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/instructor_biography'] = array('en_instructor_biography.image');
    //     }

    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/class_room'] = array('en_classroom_class.class_thumb');
    //     }
    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/certificate_design_upload'] = array('en_corporate_certificate_configuration.attachment');
    //     }
    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/corporate_certificate_image'] = array('en_corporate_certificate_image.image');
    //     }
    //     if((Auth::provider()->check())) {
    //         $this->valid_path['public/uploads/mockTest/exam'] = array('en_classroom_mocktest_exam.exam_thumb');
    //     }
    //     if(Auth::provider()->check() || Auth::corporate()->check()) {
    //         $this->valid_path['public/uploads/mockTest/attachment'] = array('en_classroom_mocktest_attachment.attachment_name');
    //     }
        
    }

    public function fileUpload(Request $request)
    {
        $path   = $request->filePath;
        $mainfile   = $request->mainfile;
        $multiple_file   = $request->multiple_file;
        $postFix   = $request->postFix;
        $reqWidth  = $request->reqWidth;
		$reqHeight = $request->reqHeight;


        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
				$time   	= time().uniqid();
				$file   	= $request->file('uploadfile'.$postFix);

                $ext      = $file->guessClientExtension();
				$file_size 	= $file->getSize();
                // if($file_size<=524288000) {
                    if(in_array($ext, $this->valid_ext)) {
                        $isDimension = true;
                        if($reqWidth>0 || $reqHeight>0) {
                            $image_info = getimagesize($file);
                            $width = $image_info[0];
                            $height = $image_info[1];
                            if($reqWidth>0 && $reqHeight>0 && ($reqWidth!=$width || $reqHeight!=$height)) {
                                $isDimension = false;
                                $errMsg = "Image size must be ".$reqWidth."px * ".$reqHeight."px";
                            } else if($reqWidth>0 && $reqWidth!=$width) {
                                $isDimension = false;
                                $errMsg = "Image width must be ".$reqWidth."px";
                            } else if($reqHeight>0 && $reqHeight!=$height) {
                                $isDimension = false;
                                $errMsg = "Image height must be ".$reqHeight."px";
                            }
                        }
                        if($isDimension) {
                            if($file->move($path, $time.'.'.$ext)) {
                                //for creating image thumb
                                $image_ext = array('jpeg', 'jpg', 'png', 'gif');
                                if(in_array($ext, $image_ext)) {
                                    //create instance
                                    $img = Image::make($path.'/'.$time.'.'.$ext);
                                    //resize image
                                    $img->resize(80, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    //save the same file as thumb
                                    $img->save($path.'/thumb/'.$time.'.'.$ext);
                                }

                                // $fb_user_id =1;
                                // $thumbnail_path   = $path.'/testThumb';
                                // $timestamp        = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                                // $file_name        = $time;
                                // $video_path       = $path.'/'.$time.'.'.$ext;
                                // $thumbnail_image  = $time.".jpg";
                                // $time_to_image    = 10;
                                // $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image);

                                $date = new DateTime();
                                $current_date = $date->format('M j, Y');
                                $status = "~success~".$time.'.'.$ext.'~'.$file_size.'~'.Helper::fileSizeConvert($file_size).'~'.$current_date.'~'.$mainfile;


                            } else {
                                $status = '~Upload Fail: Unknown error occurred!';
                            }
                        } else {
                            $status = '~Upload Fail: '.$errMsg;
                        }
                    } else {
                        $status = '~Upload Fail: Unsupported file format!';
                    }
                // } else {
                //     $status = '~Upload Fail: File size ('.Helper::fileSizeConvert($file_size).') exceeded the limit!';
                // }
            } else {
                $status = '~Bad request!';
            }
        } else {
            $status = '~Upload Fail: Permission access denied!';
        }
        return $status;
    }

    public function fileUnlink(Request $request) {
        $path   = $request->filePath;
        $file_name  = $request->image;
		$multiple_file  = $request->multiple_file;

        if(!empty($file_name) && !empty($path) && array_key_exists($path, $this->valid_path)) {
            $existChk=0;
            $valid_path_ar = $this->valid_path;
            foreach($valid_path_ar[$path] as $tbl_field) {
                $tbl_field_ar = explode('.', $tbl_field);
                if(count($tbl_field_ar)==2) {
                    if(DB::table($tbl_field_ar[0])->where($tbl_field_ar[1], $file_name)->first()) { $existChk=1; }
                }
            }

			//for image thumb
			$image_ext = array('jpeg', 'jpg', 'png', 'gif');
			$file_name_array = explode('.', $file_name);
			if(in_array($file_name_array[1], $image_ext)) {
				//delete thumb from path
				File::delete($path.'/thumb/'.$file_name);
			}

            if($existChk==0) { File::delete($path.'/'.$file_name); }
        }
    }

	public function multipleFileUnlink(Request $request) {
        $path   = $request->filePath;
        $file_name  = $request->file_name;

        if(!empty($file_name) && !empty($path) && array_key_exists($path, $this->valid_path)) {
            $existChk=0;
            $valid_path_ar = $this->valid_path;
            foreach($valid_path_ar[$path] as $tbl_field) {
                $tbl_field_ar = explode('.', $tbl_field);
                if(count($tbl_field_ar)==2) {
                    if(DB::table($tbl_field_ar[0])->where($tbl_field_ar[1], $file_name)->first()) { $existChk=1; }
                }
            }

			//for image thumb
			$image_ext = array('jpeg', 'jpg', 'png', 'gif');
			$file_name_array = explode('.', $file_name);
			if(in_array($file_name_array[1], $image_ext)) {
				//delete thumb from path
				File::delete($path.'/thumb/'.$file_name);
			}

			if($existChk==0) { File::delete($path.'/'.$file_name); }
			return $path.'/'.$file_name;
        }
    }


    public function videoUpload(Request $request)
    {
        $path   = $request->filePath;
        $multiple_file   = $request->multiple_file;
        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $time       = time();
                $file       = $request->file('uploadfile');
                $ext        = $file->guessClientExtension();
                $file_size  = $file->getSize();
                if(in_array($ext, $this->valid_ext)) {
                    if($file->move($path, $time.'.'.$ext)) {
                        //for creating image thumb
                        $image_ext = array('jpeg', 'jpg', 'png', 'gif');
                        if(in_array($ext, $image_ext)) {
                            //create instance
                            $img = Image::make($path.'/'.$time.'.'.$ext);
                            //resize image
                            $img->resize(80, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            //save the same file as thumb
                            $img->save($path.'/thumb/'.$time.'.'.$ext);
                        }

                        // $fb_user_id =1;
                        // $thumbnail_path   = $path.'/testThumb';
                        // $timestamp        = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
                        // $file_name        = $time;
                        // $video_path       = $path.'/'.$time.'.'.$ext;
                        // $thumbnail_image  = $time.".jpg";
                        // $time_to_image    = 10;
                        // $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image);

                        $date = new DateTime();
                        $current_date = $date->format('M j, Y');
                        $status = "~success~".$time.'.'.$ext.'~'.$file_size.'~'.Helper::fileSizeConvert($file_size).'~'.$current_date;


                        $date = new DateTime();
                        $current_date = $date->format('M j, Y');
                        $status = "~success~".$time.'.'.$ext.'~'.$file_size.'~'.Helper::fileSizeConvert($file_size).'~'.$current_date;

                    } else {
                        $status = '~Upload Fail: Unknown error occurred!';
                    }
                } else {
                    $status = '~Upload Fail: Unsupported file format!';
                }
            } else {
                $status = '~Bad request!';
            }
        } else {
            $status = '~Upload Fail: Permission access denied!';
        }
        return $status;
    }

}
