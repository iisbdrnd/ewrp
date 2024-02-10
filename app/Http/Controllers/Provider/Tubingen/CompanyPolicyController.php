<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TubCompanyPolicy_provider;

class CompanyPolicyController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.companyPolicy.list', $data);
    }

    public function companyPolicyListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['companyPolicies'] = TubCompanyPolicy_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('title', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.companyPolicy.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.companyPolicy.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'title'        => 'required',
            'description'     => 'required'
        ]);

        if ($validator->passes()) {
            TubCompanyPolicy_provider::create($input);
            $output['messege'] = 'Company policy has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['chooseUs'] = TubCompanyPolicy_provider::valid()->find($id);
        return view('provider.eastWest.companyPolicy.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'title'        => 'required',
            'description'  => 'required'
        ]);

        if ($validator->passes()) {
            TubCompanyPolicy_provider::valid()->find($id)->update($input);
            $output['messege'] = 'Company policy has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        TubCompanyPolicy_provider::valid()->find($id)->delete();
    }
}
