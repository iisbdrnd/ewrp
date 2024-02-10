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
use App\Model\TrainingFacilityGalleries_provider;

class TrainingFacilityGalleryController extends Controller{

    public function index(Request $request) {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.trainingFacilityGallery.list', $data);
    }

    public function trainingFacilitiesGalleryImageListData(Request $request){
        $data = $request->all();
        $search = $request->search;

        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['gallery_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['photoGalleries'] = TrainingFacilityGalleries_provider::valid()
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);
            
        return view('provider.eastWest.trainingFacilityGallery.listData', $data);
    }
    
    public function create(Request $request){
        $data['inputData'] = $request->all();
        return view('provider.eastWest.trainingFacilityGallery.create', $data);
    }

    public function store(Request $request){   
        $output = array();
        $input = $request->all();
        dd(55);
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
        return view('provider.eastWest.trainingFacilityGallery.update', $data);
    }

    public function update(Request $request, $id){
        $output = array();
        $input = $request->all();
        $validator = Validator::make($input, [
            'gallery_name'  => 'required',
            'description' => 'required'
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
        $images = TrainingFacilityGalleries_provider::valid()->where('photo_gallery_id', $photoGallery->id)->get();

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
    public function trainingFacilitiesGalleryImage(Request $request) {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.trainingFacilityGallery.list', $data);
    }

    public function facilityGalleryImageListData(Request $request){
        $data = $request->all();
        $search = $request->search;
        $photo_gallery_id = $request->gallery_id;

        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['image_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['galleryImages'] = TrainingFacilityGalleries_provider::valid()->get();
        return view('provider.eastWest.trainingFacilityGallery.listData', $data);
    }

    public function trainingFacilitiesGalleryImageDestroy($id){
        $image = TrainingFacilityGalleries_provider::valid()->where('id', $id)->first();

        File::delete(public_path().'/uploads/gallery/'.$image->image_thumb);
        File::delete(public_path().'/uploads/gallery/thumb/'.$image->image_thumb);

        $image->delete();
    }

    public function gallerySortingListData (Request $request){

          $data['title'] = $data['pageTitle'] = 'Photo Album Sorting';
        $data['albumImages'] = TubPhotoGallaries_provider::valid('valid', 1)
        ->orderBy('sl_no')->get();

        return view('provider.eastWest.trainingFacilityGallery.sorting', $data);
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


   
    
}

