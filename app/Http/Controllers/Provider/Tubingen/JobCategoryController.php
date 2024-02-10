<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\JobCategory_provider;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.jobCategory.list', $data);
    }

    public function jobCategoryListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['jobCategories'] = JobCategory_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.jobCategory.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.jobCategory.create', $data);
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
            JobCategory_provider::create([
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
        $data['jobCategory'] = JobCategory_provider::valid()->find($id);
        return view('provider.eastWest.jobCategory.update', $data);
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
            JobCategory_provider::valid()->find($id)->update([
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
        JobCategory_provider::valid()->find($id)->delete();
    }
}
