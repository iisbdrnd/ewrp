<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Chain_provider;
use App\Model\EmployeeUser_provider;
use App\Model\ServiceCategory_provider;


class ChainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('provider.admin.chain.list', $data);
    }

    public function chainListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['chains'] = Chain_provider::valid()
            ->where(function($query) use ($search)
            {
                $query->where('cat_id', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.admin.chain.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['categories'] = ServiceCategory_provider::valid()->orderBy('name', 'asc')->get();
        $data['employees'] = EmployeeUser_provider::valid()->orderBy('name', 'asc')->get();

        return view('provider.admin.chain.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        $category_id = $request->cat_id;
        $type = $request->type;
        $serial_employee= $request->serial_employe;
        
        $validator = Validator::make($input, [
            'type' => 'required'
        ]);

        if ($validator->passes()) {
            Chain_provider::create([
                'type' => $type,
                'cat_id' => $category_id,
                'chain' => json_encode($serial_employee),

            ]);

            $output['messege'] = 'Chain has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);

    }


    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Chain_provider::valid()->find($id)->delete();
    }

    public function chainAction($id)
    {
        $data = $request->all();
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            Chain_provider::where('valid', 1)->find($sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }
        $output['msg'] = 'success';
        echo json_encode($output);
    }
}
