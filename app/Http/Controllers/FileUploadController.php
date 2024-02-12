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

use Intervention\Image\ImageManager;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\User_config_user;

class FileUploadController extends Controller
{
    // Only For Hacking Security (ignore ext in js)
    public $valid_ext = array('jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'csv', 'xls', 'xlsx', 'zip', 'rar', 'tar', 'txt');
    public $valid_path = array();

    function  __construct() {
        if((Auth::user()->check())) {
            if(Helper::userAccess('crm.crm.corporateCompany.create')) {
                $this->valid_path['public/uploads/logo'] = array('crm_corporate_company.logo');
            }
        }

        if((Auth::user()->check())) {
                $this->valid_path['public/uploads/user_profile_images'] = array('employee_basic_info.image');
        }
        
        if((Auth::admin()->check())) {
            $this->valid_path['public/uploads/logo'] = array('project_info.logo');
        }

        if((Auth::user()->check())) {
            $this->valid_path['public/uploads/logo'] = array('project_info.logo');
        }
    }

    public function fileUpload(Request $request)
    {
        $path   = $request->filePath;
		$multiple_file   = $request->multiple_file;
        if(!empty($path) && array_key_exists($path, $this->valid_path)) {
            if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
				//$file_size = File::size($path.'/'.$time.'.'.$ext);
				$time   	= time();
				$file   	= $request->file('uploadfile');
				$ext    	= $file->guessClientExtension();
				$file_size 	= $file->getSize();

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
			if(in_array($file_name_arroay[1], $image_ext)) {
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

}