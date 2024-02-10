<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use File;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\NoticeBoardCategory_provider;
use App\Model\NewsEvent_provider;

class NewsEventController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.newsEvent.list', $data);
    }

    public function newsEventListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['noticeList'] = NewsEvent_provider::where('valid', 1)
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.newsEvent.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.newsEvent.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];
        
        if (count($fau_attachment) > 1) {
            $output['messege'] = 'You can\'t attach more then one attachment';
            $output['msgType'] = 'danger';
        }else{
            $validator = Validator::make($input, [
                'title'           => 'required',
                'news_event_type' => 'required'
            ]);

            if ($validator->passes()) {
                NewsEvent_provider::create([
                    'title'                => $request->title,
                    'news_event_type'      => $request->news_event_type,
                    'external_link'        => $request->external_link,
                    "attachment_name"      => !empty($fau_attachment) ? $fau_attachment[0]: null, 
                    "attachment_real_name" => !empty($fau_attachment_real_name) ? $fau_attachment_real_name[0]: null
                ]);
                
                $output['messege'] = 'News Event has been created';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['newsEvent'] = NewsEvent_provider::valid()->find($id);
        return view('provider.eastWest.newsEvent.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        
        $fau_attachment = (!empty($request->fau_attachment)) ? $request->fau_attachment : [];
        $fau_attachment_real_name = (!empty($request->fau_attachment_real_name)) ? $request->fau_attachment_real_name : [];

        if (count($fau_attachment) > 1) {
            $output['messege'] = 'You can\'t attach more then one attachment';
            $output['msgType'] = 'danger';
        }else{
            $validator = Validator::make($input, [
                'title'           => 'required',
                'news_event_type' => 'required'
            ]);

            if ($validator->passes()) {
                $notice = NewsEvent_provider::valid()->find($id);
                if ($notice->attachment_name != $fau_attachment[0]) {
                    File::delete(public_path('uploads/notice_attachments/'.$notice->attachment_name));
                }
                // else{
                //     $notice->update([
                //         "attachment_name" => !empty($fau_attachment) ? $fau_attachment[0] : null, 
                //         "attachment_real_name"=> !empty($fau_attachment_real_name) ? $fau_attachment_real_name[0]: null
                //     ]);
                // }
               
                $notice->update([
                    'title'                => $request->title,
                    'news_event_type'      => $request->news_event_type,
                    'external_link'        => $request->news_event_type == 2 ? $request->external_link : null,
                    "attachment_name"      => !empty($request->news_event_type == 1) ? $fau_attachment[0] : null, 
                    "attachment_real_name" => !empty($request->news_event_type == 1) ? $fau_attachment_real_name[0]: null
                ]);


                $output['messege'] = 'News Event has been updated';
                $output['msgType'] = 'success';
            } else {
                $output = Helper::vError($validator);
            }
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        NewsEvent_provider::valid()->find($id)->delete();
    }
}
