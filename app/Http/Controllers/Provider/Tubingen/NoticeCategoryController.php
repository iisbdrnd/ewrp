<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\NoticeBoardCategory_provider;

class NoticeCategoryController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.noticeCategory.list', $data);
    }

    public function noticeCategoryListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['jobCategories'] = NoticeBoardCategory_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('sl_no')
            ->paginate($paginate->perPage);

        return view('provider.eastWest.noticeCategory.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.noticeCategory.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();

        $slug = str_replace(' ', '-', $request->name);
        
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            NoticeBoardCategory_provider::create([
                'name' => $request->name,
                'slug' => $slug
            ]);
            $output['messege'] = 'Job Category has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['jobCategory'] = NoticeBoardCategory_provider::valid()->find($id);
        return view('provider.eastWest.noticeCategory.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();

        $slug = str_replace(' ', '-', $request->name);
        
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            NoticeBoardCategory_provider::valid()->find($id)->update([
                'name' => $request->name,
                'slug' => $slug
            ]);
            $output['messege'] = 'Job Category has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        NoticeBoardCategory_provider::valid()->find($id)->delete();
    }

    public function noticeCategorySorting (Request $request){
        $data['noticeCategories'] = NoticeBoardCategory_provider::valid()->orderBy('sl_no')->get();
        
        return view('provider.eastWest.noticeCategory.sorting', $data);
    }
    public function noticeCategorySortingAction(Request $request){
        $data = $request->all();
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            NoticeBoardCategory_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

        $output['messege'] = 'Notice Category sorting successfully';
        $output['msgType'] = 'success';
        
        echo json_encode($output); 
    }
}
