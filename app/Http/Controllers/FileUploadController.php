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

use App\Model\TubGallary_provider;
use App\Model\FacilityHeadOfficeGallery_provider;
use App\Model\TrainingFacilityGalleries_provider;
use App\Model\TubPhotoGallaries_provider;

class FileUploadController extends Controller{
    // Only For Hacking Security (ignore ext in js)
    public $valid_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'csv', 'xls', 'xlsx', 'zip', 'rar', 'tar', 'txt', 'mng', 'avchd', 'wmv', 'avi', 'mov', 'mp4', 'mpg', 'html');
    public $valid_path = array();

    function  __construct() {
        // if((Auth::guard('softAdmin')->check())) {
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

            $this->valid_path['public/uploads/social_logo'] = array('social_link.social_logo');
            $this->valid_path['public/uploads/commercial'] = array('commercial.video_thumb');
            $this->valid_path['public/uploads/narrative'] = array('narrative.video_thumb');
            $this->valid_path['public/uploads/updateCv'] = array('updateCv.attachment');
            $this->valid_path['public/uploads/provider_user_images'] = array('en_provider_user_info.image');
        // } 
        // if((Auth::guard('provider')->user())) {
            // if(Helper::providerUserAccess('provider.tubinjen.provider.tubinjen.managementTeam.create')) {
                $this->valid_path['public/uploads/managementTeam'] = array('tub_management_team.image');
                $this->valid_path['public/uploads/customers'] = array('tub_customers.image');
                $this->valid_path['public/uploads/products'] = array('tub_product.image');
                $this->valid_path['public/uploads/sustainabilities'] = array('tub_sustainability.image');
                $this->valid_path['public/uploads/banner'] = array('tub_banner.banner');
                $this->valid_path['public/uploads/client'] = array('tub_client.image');
                $this->valid_path['public/uploads/facilityHeadOffice'] = array('facility_head_office_gallery.image_thumb');
                $this->valid_path['public/uploads/trainingFacilities'] = array('training_facility_gallery.image_thumb');
                $this->valid_path['public/uploads/job_opening_attachments'] = array('job_opening_attachments.attachment_name');
                $this->valid_path['public/uploads/license_attachments'] = array('licenses.attachment_name');
                $this->valid_path['public/uploads/notice_attachments'] = array('notice_board.attachment_name');
                $this->valid_path['public/uploads/news_event_attachments'] = array('news_event.attachment_name');
            // }
        // }

    }

    public function fileUpload(Request $request)
    {
        $path           = $request->filePath;
        $multiple_file  = $request->multiple_file;
        $postFix        = $request->postFix;
        $reqWidth       = $request->reqWidth;
        $reqHeight      = $request->reqHeight;
        $resizeWidth    =$request->resizeWidth;
        $resizeHeight   =$request->resizeHeight;
        
        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $time       = time();
                $file       = $request->file('uploadfile'.$postFix);
                $ext        = $file->guessClientExtension();
                $file_size  = $file->getSize();
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
                            // $path = public_path($path);
                            if($file->move($path, $time.'.'.$ext)) {
                                //for creating image thumb
                                $image_ext = array('jpeg', 'jpg', 'png', 'gif');
                                if(in_array($ext, $image_ext)) {
                                    //create instance
                                    $img = Image::make($path.'/'.$time.'.'.$ext);
                                    // dd($img);
                                    //resize image
                                    if($resizeWidth>0 || $resizeHeight>0){
                                         $img->resize($resizeWidth, $resizeHeight, function ($constraint) {
                                            $constraint->aspectRatio();
                                         });
                                    }
                                    $img->resize(80, 80, function ($constraint) {
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

    public function galleryImageStore(Request $request, $id)
    {
        $path   = $request->filePath;
        $mainfile   = $request->mainfile;
        $multiple_file   = $request->multiple_file;
        $postFix   = $request->postFix;
        $reqWidth  = $request->reqWidth;
		$reqHeight = $request->reqHeight;

        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
				// $time   	= time().uniqid();
                 // $time    = time();
				$file   	= $request->file('uploadfile'.$postFix);

                $ext      = $file->guessClientExtension();
				$file_size 	= $file->getSize();
                 $image_name=time().uniqid().'.'. $ext;
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
                            if($file->move($path,$image_name)) {
                                //for creating image thumb
                                $image_ext = array('jpeg', 'jpg', 'png', 'gif');
                                if(in_array($ext, $image_ext)) {
                                    //create instance
                                    $img = Image::make($path.'/'.$image_name);
                                    //resize image
                                    $img->resize(500, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    //save the same file as thumb
                                    $img->save($path.'/thumb/'.$image_name);
                                     //SaveImageDatabase                           
                                   
                                    $max_sl_no = TubGallary_provider::where('valid', 1)
                                    ->where('photo_gallery_id', $id)
                                    ->max('sl_no');
                                    
                                    //sl_no fro assending decending
                                    // if ($max_sl_no==null || $max_sl_no==0){
                                    //      $max_sl_no=1;
                                    //  }else{
                                        $max_sl_no=$max_sl_no+1;
                                    // }
                               
                                        // RzAshramGallery_provider::create([
                                        //     "photo_gallery_id"           => $id,
                                        //     "image_thumb"                => $image_name,
                                        //     "image_attachment_real_name" => $image_name,
                                        //     "sl_no" =>  $max_sl_no  

                                        //     //$attachment_real_name[$index]
                                        // ]);
                                    TubGallary_provider::create([
                                        "photo_gallery_id"           => $id,
                                        "image_thumb"                => $image_name,
                                        "image_attachment_real_name" => $image_name
                                    ]);

                                    //for album image (first sl no of gallery table )
                            

                                    $first_image = TubGallary_provider::where('valid', 1)
                                        ->where('photo_gallery_id', $id)
                                        ->orderBy('sl_no') 
                                        ->first(); 

                                    TubPhotoGallaries_provider::valid()->find($id)->update([
                                        "gallery_thumb" => $first_image->image_thumb
                                    ]);       
                        
                                }
                                $date = new DateTime();
                                $current_date = $date->format('M j, Y');
                                $status = "~success~".$image_name.'~'.$file_size.'~'.Helper::fileSizeConvert($file_size).'~'.$current_date.'~'.$mainfile;

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
    public function trainingFacilitiesGalleryImageAction(Request $request, $id)
    {
        $path   = $request->filePath;
        $mainfile   = $request->mainfile;
        $multiple_file   = $request->multiple_file;
        $postFix   = $request->postFix;
        $reqWidth  = $request->reqWidth;
		$reqHeight = $request->reqHeight;

        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
				// $time   	= time().uniqid();
                 // $time    = time();
				$file   	= $request->file('uploadfile'.$postFix);

                $ext      = $file->guessClientExtension();
				$file_size 	= $file->getSize();
                 $image_name=time().uniqid().'.'. $ext;
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
                            if($file->move($path,$image_name)) {
                                //for creating image thumb
                                $image_ext = array('jpeg', 'jpg', 'png', 'gif');
                                if(in_array($ext, $image_ext)) {
                                    //create instance
                                    $img = Image::make($path.'/'.$image_name);
                                    //resize image
                                    $img->resize(500, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    //save the same file as thumb
                                    $img->save($path.'/thumb/'.$image_name);
                                     //SaveImageDatabase                           
                                   
                                    $max_sl_no = TrainingFacilityGalleries_provider::where('valid', 1)
                                    ->where('photo_gallery_id', $id)
                                    ->max('sl_no');
                                    
                                    //sl_no fro assending decending
                                    // if ($max_sl_no==null || $max_sl_no==0){
                                    //      $max_sl_no=1;
                                    //  }else{
                                        $max_sl_no=$max_sl_no+1;
                                    // }
                               
                                        // RzAshramGallery_provider::create([
                                        //     "photo_gallery_id"           => $id,
                                        //     "image_thumb"                => $image_name,
                                        //     "image_attachment_real_name" => $image_name,
                                        //     "sl_no" =>  $max_sl_no  

                                        //     //$attachment_real_name[$index]
                                        // ]);
                                    TrainingFacilityGalleries_provider::create([
                                        "photo_gallery_id"           => $id,
                                        "image_thumb"                => $image_name,
                                        "image_attachment_real_name" => $image_name
                                    ]);

                                    //for album image (first sl no of gallery table )
                            

                                    $first_image = TrainingFacilityGalleries_provider::where('valid', 1)
                                        ->where('photo_gallery_id', $id)
                                        ->orderBy('sl_no') 
                                        ->first(); 

                                    TubPhotoGallaries_provider::valid()->find($id)->update([
                                        "gallery_thumb" => $first_image->image_thumb
                                    ]);       
                        
                                }
                                $date = new DateTime();
                                $current_date = $date->format('M j, Y');
                                $status = "~success~".$image_name.'~'.$file_size.'~'.Helper::fileSizeConvert($file_size).'~'.$current_date.'~'.$mainfile;

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
    public function facilitiesGalleryImageAction(Request $request, $id)
    {
        $path   = $request->filePath;
        $mainfile   = $request->mainfile;
        $multiple_file   = $request->multiple_file;
        $postFix   = $request->postFix;
        $reqWidth  = $request->reqWidth;
		$reqHeight = $request->reqHeight;

        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
				// $time   	= time().uniqid();
                 // $time    = time();
				$file   	= $request->file('uploadfile'.$postFix);

                $ext      = $file->guessClientExtension();
				$file_size 	= $file->getSize();
                 $image_name=time().uniqid().'.'. $ext;
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
                            if($file->move($path,$image_name)) {
                                //for creating image thumb
                                $image_ext = array('jpeg', 'jpg', 'png', 'gif');
                                if(in_array($ext, $image_ext)) {
                                    //create instance
                                    $img = Image::make($path.'/'.$image_name);
                                    //resize image
                                    $img->resize(500, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    //save the same file as thumb
                                    $img->save($path.'/thumb/'.$image_name);
                                     //SaveImageDatabase                           
                                   
                                    $max_sl_no = FacilityHeadOfficeGallery_provider::where('valid', 1)
                                    ->where('photo_gallery_id', $id)
                                    ->max('sl_no');
                                    
                                    //sl_no fro assending decending
                                    // if ($max_sl_no==null || $max_sl_no==0){
                                    //      $max_sl_no=1;
                                    //  }else{
                                        $max_sl_no=$max_sl_no+1;
                                    // }
                               
                                    FacilityHeadOfficeGallery_provider::create([
                                        "photo_gallery_id"           => 2,
                                        "image_thumb"                => $image_name,
                                        "image_attachment_real_name" => $image_name
                                    ]);

                                    //for album image (first sl no of gallery table )
                            

                                    $first_image = FacilityHeadOfficeGallery_provider::where('valid', 1)
                                        ->where('photo_gallery_id', $id)
                                        ->orderBy('sl_no') 
                                        ->first(); 

                                    // TubPhotoGallaries_provider::valid()->find($id)->update([
                                    //     "gallery_thumb" => $first_image->image_thumb
                                    // ]);       
                        
                                }
                                $date = new DateTime();
                                $current_date = $date->format('M j, Y');
                                $status = "~success~".$image_name.'~'.$file_size.'~'.Helper::fileSizeConvert($file_size).'~'.$current_date.'~'.$mainfile;

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
        $file_name  = $request->file_name;
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
