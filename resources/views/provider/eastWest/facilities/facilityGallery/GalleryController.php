<?php

namespace App\Http\Controllers\Provider\TManager;
use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use File;
use Validator;
use DateTime;
use Collection;
use DateInterval;
use Image;

use App\Http\Requests;
use App\Model\CrmActivitiesTask;
use App\Model\CrmCampaign;
use App\Http\Controllers\Controller;
use App\Model\RzGallery_provider;
use App\Model\RzPhotoGallery_provider;


class GalleryController extends Controller {

    public function index(Request $request) {
        $data['inputData'] = $request->all();
        return view('provider.TManager.photoGallery.list', $data);
    }

    public function galleryListData(Request $request){
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['gallery_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['photoGalleries'] = RzPhotoGallery_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('gallery_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.TManager.photoGallery.listData', $data);
    }
    
    public function create(Request $request){
        $data['inputData'] = $request->all();
        return view('provider.TManager.photoGallery.create', $data);
    }

    public function store(Request $request){  
        dd($request-all()); 
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'gallery_name'  => 'required',
            'gallery_thumb' => 'required'
        ]);

        if ($validator->passes()) {
            RzPhotoGallery_provider::create([
                "gallery_name"  => $request->gallery_name,
                "description"   => $request->description,
                "gallery_thumb" => $request->gallery_thumb
            ]);

            $output['messege'] = 'Photo Gallery has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }

    public function edit($id){
        $data['gallery'] = RzPhotoGallery_provider::valid()->find($id);
        return view('provider.TManager.photoGallery.update', $data);
    }

    public function update(Request $request, $id){
        $output = array();
        $input = $request->all();
        $validator = Validator::make($input, [
            'gallery_name'  => 'required',
            'gallery_thumb' => 'required'
        ]);

        if ($validator->passes()) {
            RzPhotoGallery_provider::valid()->find($id)->update([
                "gallery_name"  => $request->gallery_name,
                "description"   => $request->description,
                "gallery_thumb" => $request->gallery_thumb
            ]);
            $output['messege'] = 'Photo Gallery has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }

    public function destroy($id){
        RzPhotoGallery_provider::valid()->find($id)->delete();
    }

    //Gallery Image Uploads methods are start from here...
    public function galleryImage(Request $request) {
        $data['inputData'] = $request->all();
        $id = $request->photo_gallery_id;
        $data['gallery'] = RzPhotoGallery_provider::valid()->find($id);
        return view('provider.TManager.galleryimage.list', $data);
    }

    public function galleryCoverphoto(Request $request) {
        $data['inputData'] = $request->all();
        $id = $request->photo_gallery_id;
        $data['gallery'] = RzPhotoGallery_provider::valid()->find($id);
        return view('provider.TManager.gallery.gallery_coverphoto', $data);
    }




    public function galleryImageListData(Request $request){
         // $data = $request->all();
        // $search = $request->search;
         $photo_gallery_id = $request->gallery_id;

        // $data['access'] = Helper::providerUserPageAccess($request);
        // $ascDesc = Helper::ascDesc($data, ['image_name']);
        // $paginate = Helper::paginate($data);
        // $data['sn'] = $paginate->serial;

        // $data['galleryImages'] = RzGallery_provider::valid()
        //     ->where('photo_gallery_id', $photo_gallery_id)
        //     ->orderBy($ascDesc[0], $ascDesc[1])
        //     ->orderBy('sl_no')->get()
        //     ->paginate($paginate->perPage);
        // $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'narrative Sorting';
        $data['galleryImages'] = RzGallery_provider::valid()
        ->where('photo_gallery_id', $photo_gallery_id)
        ->orderBy('sl_no')->get();

        return view('provider.TManager.galleryimage.listData', $data);
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
                $time       = time().uniqid();
                $file       = $request->file('uploadfile'.$postFix);

                $ext      = $file->guessClientExtension();
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



    

    public function galleryImageStore(Request $request, $id){   
        $output = array();
        $input = $request->all();
        $photo_gallery_id = $id; //photo album folder id

        $validator = [
            // 'attach' => 'required'
        ];

         $imageData = $request->thumbnail;
        // dd($imageData);
        if ($imageData != "") {

            $image_name=time().'.png';
            $path='/uploads/gallery/';
            $crop_image_data=$request->thumbnail;
            $real_image_data=$request->real_image;
           
             //Helper::cropImageManagement($image_name,$path,$image_data);
             Helper::ThumbcropWithRealImage($image_name,$path,$crop_image_data,$real_image_data);

            
            $validator = Validator::make($input, $validator);

            if ($validator->passes()) {
                // $galleryImages = (!empty($request->image_attachment)) ? $request->image_attachment : [];
                // $attachment_real_name = (!empty($request->image_attachment_real_name)) ? $request->image_attachment_real_name : [];
                
                //foreach ($galleryImages as $index=>$attachm) {
                    RzGallery_provider::create([
                        "photo_gallery_id"           => $photo_gallery_id,
                        "image_thumb"                => $image_name,
                        "image_attachment_real_name" => $image_name //$attachment_real_name[$index]
                    ]);
                //}

                $output['messege'] = 'Image has been Uploaded';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }else {
            $output['messege'] = 'Image is empty';
            $output['msgType'] = 'danger';

        }
        echo json_encode($output);
    }



    public function updateCoverPhoto(Request $request)
    {

        $output = array();
        $validator = Validator::make($request->all(), [
                //'main_image' => 'required',
                //'thumbnail'  => 'required'
        ]);

        //dd($request->gallery_id);

        $imageData = $request->cover_photo;
        $cover_photo_real=$request->cover_photo_real;
        // dd($imageData);
        if ($imageData != "" && $cover_photo_real!= "") {

                  $image_name=time().uniqid().'.jpeg';
                  $path='/uploads/gallery_coverphoto/';
                  $crop_image_data=$request->cover_photo;
                  $previous_image_name=$request->thumbnail;
                  
//dd($previous_image_name);
                 // Helper::cropImageManagement($previous_image_name,$image_name,$path,$crop_image_data);
              

        File::delete(public_path().$path.$previous_image_name);
        File::delete(public_path().$path.'/thumb/'.$previous_image_name);
        File::delete(public_path().$path.'/thumb/sthumb/'.$previous_image_name);

        //Crop Image
        $image_data = $crop_image_data; //$_POST['gallery_thumb'];
       
        list($type, $image_data) = explode(';', $image_data);
        list(, $image_data)      = explode(',', $image_data);

        $image_data = base64_decode($image_data);

    


        // $imageName = time().'.png';
        $imageName= $image_name;

       

        file_put_contents(public_path().$path.$imageName, $image_data);
        


          //Real Image
        $cover_photo_real = $cover_photo_real; //$_POST['gallery_thumb'];
        list($type, $cover_photo_real) = explode(';', $cover_photo_real);
        list(, $cover_photo_real)      = explode(',', $cover_photo_real);

        $cover_photo_real = base64_decode($cover_photo_real);


        //Real Image 
        file_put_contents(public_path().$path.'/thumb/'.$imageName, $cover_photo_real);
        //create instance

        $img = Image::make(public_path().$path.'/thumb/'.$imageName);

        //resize image
        $img->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path().$path.'/thumb/'.$imageName); //save the same file as

        //Small Thumb
          //resize image
        $img->resize(80, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path().$path.'/thumb/sthumb/'.$imageName); //save the same file as


                if ($validator->passes()) {

                    // $viewFinder = RzViewFinder_Provider::valid()->find($id);
                    // if($viewFinder->main_image != $request->main_image) {
                    //     File::delete(public_path().'/uploads/view_finder/'.$viewFinder->main_image);
                    //     File::delete(public_path().'/uploads/view_finder/thumb/'.$viewFinder->main_image);
                    // }
                    // if($viewFinder->thumbnail != $request->thumbnail) {
                    //     File::delete(public_path().'/uploads/view_finder/thumbnail/'.$viewFinder->thumbnail);
                    //     File::delete(public_path().'/uploads/view_finder/thumbnail/thumb/'.$viewFinder->thumbnail);
                    // }
                    RzPhotoGallery_provider::find($request->gallery_id)->update([
                        "cover_photo" => $image_name
                    ]);
                    $output['messege'] = 'Cover Photo Updated';
                    $output['msgType'] = 'success';

                } else {
                    $output = Helper::vError($validator);
                }
        }else{
            $output['messege'] = 'image is empty';
            $output['msgType'] = 'danger';
        }
        echo json_encode($output);
    }


    public function albumImageStore(Request $request, $id){   
     //    $image_code = '';
     //    $path='/uploads/gallery/';
     // //$images = $request->file('file');
     //  $input = $request->all();
     // dd ($input);
     // foreach($images as $image)
     // {
     //  // $new_name = rand() . '.' . $image->getClientOriginalExtension();
     //  // $image->move(public_path('images'), $new_name);
     //  // $image_code .= '<div class="col-md-3" style="margin-bottom:24px;"><img src="/images/'.$new_name.'" class="img-thumbnail" /></div>';
     // }

     // $output = array(
     //  'success'  => 'Images uploaded successfully',
     //  'image'   => $image_code
     // );
     //    echo json_encode($output);
     //return response()->json($output);
    }

    public function galleryImageDestroy($id){
        $photo = RzGallery_provider::valid()->find($id);

        File::delete(public_path().'/uploads/gallery/thumb/'.$photo->image_thumb);
        File::delete(public_path().'/uploads/gallery/'.$photo->image_thumb);

        $photo->delete();
    }

    public function gallerySortingAction(Request $request){
         $data = $request->all();

        
        $sorted = $request->sorted;

        $i=1;
        foreach($sorted as $sortId) {
            RzGallery_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

   //for albun thumb selection (first sl_no)
        $gallery_id = RzGallery_provider::where('valid', 1)
               ->where('id', $sorted[0])
               ->first(); 

        $first_image = RzGallery_provider::where('valid', 1)
               ->where('photo_gallery_id', $gallery_id->photo_gallery_id)
               ->orderBy('sl_no') 
               ->first(); 

                RzPhotoGallery_provider::valid()->find($gallery_id->photo_gallery_id)->update([
                "gallery_thumb" => $first_image->image_thumb,
                 ]); 


        $output['msg'] = 'success';
        
        echo json_encode($output); 
    }
    
}