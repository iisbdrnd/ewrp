<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use File;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TubBanner_provider;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.banner.list', $data);
    }
    public function bannerListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['banners'] = TubBanner_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.banner.listData', $data);
    }
    public function create()
    {
        return view('provider.eastWest.banner.create');
    }

    public function store(Request $request)
    {
        $output = array();

        // $validator = Validator::make($request->all(), [
        //     'title'       => 'required',
        //     'mini_title'  => 'required',
        //     'description' => 'required',
        //     'banner'      => 'required'
        // ]);

        // if ($validator->passes()) {
            TubBanner_provider::create([
                'title'               => $request->title,
                'mini_title'          => $request->mini_title,
                'description'         => $request->description,
                'btn_text'            => $request->btn_text,
                'btn_link'            => $request->btn_link,
                'banner'              => $request->banner
            ]);
            $output['messege'] = 'Banner Create Successfully';
            $output['msgType'] = 'success';
        // } else {
        //     $output = Helper::vError($validator);
        // }
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['banner'] = TubBanner_provider::valid()->find($id);
        return view('provider.eastWest.banner.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();

        // $validator = Validator::make($request->all(), [
        //     'title'       => 'required',
        //     'mini_title'  => 'required',
        //     'description' => 'required',
        //     'banner'      => 'required'
        // ]);

        // if ($validator->passes()) {
            TubBanner_provider::valid()->find($id)->update($input);
            $output['messege'] = 'Banner has been updated';
            $output['msgType'] = 'success';
        // } else {
        //     $output = Helper::vError($validator);
        // }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        $banner = TubBanner_provider::valid()->find($id);
        if (!empty($banner)) {
            File::delete(public_path('uploads/banner/'.$banner->banner));
            $banner->delete();
        } else {
            echo 'No Job Found';
        }
        
    }
}

