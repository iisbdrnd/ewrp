<?php

namespace App\Http\Controllers\Provider\Tubingen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use File;
use Helper;
use Validator;
use DateTime;
use Collection;
use DateInterval;

use App\Http\Requests;
use App\Model\TubPhotoGallaries_provider;
use App\Model\TubGallary_provider;

class GalleryController extends Controller{

    public function index(Request $request) {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.gallery.list', $data);
    }

    public function galleryListData(Request $request){
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['gallery_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['photoGalleries'] = TubPhotoGallaries_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('gallery_name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('sl_no')
            ->paginate($paginate->perPage);
            
        return view('provider.eastWest.gallery.listData', $data);
    }
    
    public function create(Request $request){
        $data['inputData'] = $request->all();
        return view('provider.eastWest.gallery.create', $data);
    }

    public function store(Request $request){   
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'gallery_name'  => 'required'
        ]);

        if ($validator->passes()) {
            TubPhotoGallaries_provider::create([
                "gallery_name"  => $request->gallery_name,
                "description"   => $request->description
            ]);

            $output['messege'] = 'Photo Gallery has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id){
        $data['gallery'] = TubPhotoGallaries_provider::valid()->find($id);
        return view('provider.eastWest.gallery.update', $data);
    }

    public function update(Request $request, $id){
        $output = array();
        $input = $request->all();
        $validator = Validator::make($input, [
            'gallery_name'  => 'required'
        ]);

        if ($validator->passes()) {
            TubPhotoGallaries_provider::valid()->find($id)->update([
                "gallery_name"  => $request->gallery_name,
                "description"   => $request->description
            ]);
            $output['messege'] = 'Photo Gallery has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function destroy($id){
        $photoGallery = TubPhotoGallaries_provider::valid()->find($id);
        $images = TubGallary_provider::valid()->where('photo_gallery_id', $photoGallery->id)->get();

        File::delete(public_path().'/uploads/ashram/'.$photoGallery->gallery_thumb);
        File::delete(public_path().'/uploads/ashram/thumb/'.$photoGallery->gallery_thumb);

        $photoGallery->delete();

        if (count($images) > 0) {
            foreach ($images as $image) {
                File::delete(public_path().'/uploads/ashramGallery/thumb/'.$image->image_thumb);
                File::delete(public_path().'/uploads/ashramGallery/'.$image->image_thumb);
                $image->delete();
            }
        }

    }




    //Gallery Image Uploads methods are start from here...
    public function galleryImage(Request $request) {
        $data['inputData'] = $request->all();
        $id = $request->photo_gallery_id;
        $data['gallery'] = TubPhotoGallaries_provider::valid()->find($id);
        return view('provider.eastWest.galleryimage.list', $data);
    }

    public function galleryImageListData(Request $request){
        $data = $request->all();
        $search = $request->search;
        $photo_gallery_id = $request->gallery_id;

        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['image_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['galleryImages'] = TubGallary_provider::valid()
            ->where('photo_gallery_id', $photo_gallery_id)
            ->orderBy('sl_no')
            ->get();
        return view('provider.eastWest.galleryimage.listData', $data);
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
                                    ->where('photo_gallery_id', $request->idParameter)
                                    ->max('sl_no');
                                    
                                    //sl_no fro assending decending
                                    // if ($max_sl_no==null || $max_sl_no==0){
                                    //      $max_sl_no=1;
                                    //  }else{
                                        $max_sl_no=$max_sl_no+1;
                                    // }
                               
                                        // RzAshramGallery_provider::create([
                                        //     "photo_gallery_id"           => $request->idParameter,
                                        //     "image_thumb"                => $image_name,
                                        //     "image_attachment_real_name" => $image_name,
                                        //     "sl_no" =>  $max_sl_no  

                                        //     //$attachment_real_name[$index]
                                        // ]);
                                    TubGallary_provider::create([
                                        "photo_gallery_id"           => $photo_gallery_id,
                                        "image_thumb"                => $image_name,
                                        "image_attachment_real_name" => $image_name
                                    ]);

                                    //for album image (first sl no of gallery table )
                            

                                    $first_image = TubGallary_provider::where('valid', 1)
                                        ->where('photo_gallery_id', $request->idParameter)
                                        ->orderBy('sl_no') 
                                        ->first(); 

                                    TubPhotoGallaries_provider::valid()->find($request->idParameter)->update([
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

    public function galleryImageDestroy($id){
        $image = TubGallary_provider::valid()->where('id', $id)->first();

        File::delete(public_path().'/uploads/gallery/'.$image->image_thumb);
        File::delete(public_path().'/uploads/gallery/thumb/'.$image->image_thumb);

        $image->delete();
    }

    public function gallerySortingListData (Request $request){

          $data['title'] = $data['pageTitle'] = 'Photo Album Sorting';
        $data['albumImages'] = TubPhotoGallaries_provider::valid('valid', 1)
        ->orderBy('sl_no')->get();

        return view('provider.eastWest.gallery.sorting', $data);
    }

    public function galleryAlbumSortingAction(Request $request){
         $data = $request->all();
      
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            TubPhotoGallaries_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }
        $output['msg'] = 'success';
        
        echo json_encode($output); 
    }

    public function gallerySortingAction(Request $request){
        $data = $request->all();
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            TubGallary_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

        //for album thumb selection (first sl_no)
        $gallery_id = TubGallary_provider::where('valid', 1)
                ->where('id', $sorted[0])
                ->first(); 
        $first_image = TubGallary_provider::where('valid', 1)
                ->where('photo_gallery_id', $gallery_id->photo_gallery_id)
                ->orderBy('sl_no') 
                ->first();
        TubPhotoGallaries_provider::valid()->find($gallery_id->photo_gallery_id)->update([
            "gallery_thumb" => $first_image->image_thumb,
        ]); 

        $output['messege'] = 'Sort Successfully';
        $output['msgType'] = 'success';
        
        echo json_encode($output); 
   }


   
    
}

