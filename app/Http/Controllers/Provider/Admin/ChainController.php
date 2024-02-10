<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Helper;
use Validator;
use App\Model\Chain_provider;
use App\Model\EmployeeUser_provider;
use App\Model\ServiceCategory_provider;

class ChainController extends Controller
{

    
    public function chainListData(Request $request)
    {
        $data = $request->all();
        $search = $request->search;
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['chainCategories'] = ServiceCategory_provider::valid()
            ->where(function($query) use ($search)
                {
                    $query->where('name', 'LIKE', '%'.$search.'%');
                })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.admin.chain.listData', $data);
    }

    public function updateChain(Request $request){
        $data['cat_id'] = $cat_id = $request->cat_id;

        $data['ServiceCategoryType'] = ServiceCategory_provider::valid()->find($cat_id);
        $data['employees'] = EmployeeUser_provider::valid()->orderBy('name', 'asc')->get();
        $data['chainInfo'] = Chain_provider::valid()->where('cat_id',$cat_id)->first();
        return view('provider.admin.chain.updateChain', $data);
    }

    public function updateChainAction(Request $request){
        $output = array();
        $input = $request->all();
        $category_id = $request->cat_id;
        $type = $request->type;
        $output['serial_employee']= $serial_employee = $request->employe_chain;
        

        $validator = [
            'type'           => 'required',
        ];
        // MENUAL
        if ($type == 1) {
            $validator['employe_chain'] = 'required';
        }

        $validator = Validator::make($input, $validator);

        if ($validator->passes()) {

            $check_exits = Chain_provider::valid()->where('cat_id', '=', $category_id)->first();
            if(!empty($check_exits)){

                $check_exits->update([
                    'type' => $type,
                    'cat_id' => $category_id,
                    'chain' => json_encode($serial_employee),
                ]);

            }else{
                Chain_provider::create([
                    'type' => $type,
                    'cat_id' => $category_id,
                    'chain' => json_encode($serial_employee),
    
                ]);
            }

            ServiceCategory_provider::valid()->find($category_id)->update([
                'approval_status'   => $type,
            ]);

            $output['messege'] = 'Chain has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }
    public function updateChaindestroy($id)
    {
        dd($id);
        Department_provider::valid()->find($id)->delete();
    }

}
