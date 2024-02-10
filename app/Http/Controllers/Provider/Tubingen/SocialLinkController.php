<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\SocialLink_provider;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.socialLink.list', $data);
    }

    public function socialLinkListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['social_link']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;
        // $project_id = Auth::guard('provider')->user()->project_id;

        $data['socialLinks'] = SocialLink_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('social_link', 'LIKE', '%'.$search.'%');
            })
            // ->where('project_id', $project_id)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.socialLink.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.socialLink.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $output = array();
        $validator = Validator::make($request->all(), [
                'social_link' => 'required',
                'social_logo' => 'required',
                'fa_icon'     => 'required'
        ]);

        if ($validator->passes()) {
            SocialLink_provider::create([
                "social_link" => $request->social_link,
                'social_logo' => $request->social_logo,
                'short_link'  => 0,
                'fa_icon'     => $request->fa_icon
            ]);
            $output['messege'] = 'Social Link has been created';
            $output['msgType'] = 'success';

        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data['link'] = SocialLink_provider::valid()->find($id);
        return view('provider.eastWest.socialLink.update', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $output = array();
        $validator = Validator::make($request->all(), [
                'social_link' => 'required',
                'social_logo' => 'required',
                'fa_icon'     => 'required'
        ]);

        if ($validator->passes()) {
            $socialLink = SocialLink_provider::valid()->find($id);
            if($socialLink->social_logo != $request->social_logo) {
                File::delete(public_path().'/uploads/social_logo/'.$socialLink->social_logo);
                File::delete(public_path().'/uploads/social_logo/thumb/'.$socialLink->social_logo);
            }
            SocialLink_provider::find($id)->update([
                "social_link" => $request->social_link,
                'social_logo' => $request->social_logo,
                'fa_icon'     => $request->fa_icon
            ]);
            $output['messege'] = 'Social Link has been Updated';
            $output['msgType'] = 'success';

        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $socialLink = SocialLink_provider::valid()->find($id);
        File::delete(public_path().'/uploads/social_logo/'.$socialLink->social_logo);
        File::delete(public_path().'/uploads/social_logo/thumb/'.$socialLink->social_logo);
        $socialLink->delete();
    }

    public function updateSortLinkSelect(Request $request)
    {
        $output = array();
        $id = $request->socialId;
        $validator = Validator::make($request->all(), []);
        $socialLink = SocialLink_provider::valid()->find($id);
        if ($validator->passes()) {
            $socialLink->update([
                'short_link' => $socialLink->short_link == 1 ? 0 : 1
            ]);
            $output['msg_title'] = 'Done !!!';
            $output['messege'] = 'Sort Link has been Updated';
            $output['messege_icon'] = 'icomoon-icon-checkmark-3';
            $output['msgType'] = 'success-notice';

        } else {
            $output['msg_title'] = 'Sorry !!!';
            $output['messege'] = 'Invite already accepted.';
            $output['messege_icon'] = 'icomoon-icon-close gritter-icon';
            $output['msgType'] = 'error-notice';
        }
        echo json_encode($output);
    }
}
