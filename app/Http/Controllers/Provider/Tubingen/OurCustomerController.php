<?php

namespace App\Http\Controllers\Provider\Tubingen;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\TubCustomers_provider;

class OurCustomerController extends Controller
{
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('provider.eastWest.ourCustomers.list', $data);
    }

    public function ourCustomersListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, []);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['customers'] = TubCustomers_provider::join('en_country', 'en_country.id', '=','tub_customers.country_id')
            ->select('tub_customers.*', 'en_country.name as country_name')
            ->where('tub_customers.valid',1)
            ->where(function($query) use ($search)
            {
                $query->where('tub_customers.name', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.eastWest.ourCustomers.listData', $data);
    }

    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['countries'] = DB::table('en_country')->get();

        return view('provider.eastWest.ourCustomers.create', $data);
    }

    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name'        => 'required',
            'country_id'  => 'required',
            'image'       => 'required'
        ]);

        $imageData = $request->thumbnail;

        if ($validator->passes()) {
            TubCustomers_provider::create([
                'name'           =>$request->name,
                'country_id'     =>$request->country_id,
                "image"          =>$request->image
            ]);
            $output['messege'] = 'Customer has been created';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    public function edit($id)
    {
        $data['countries'] = DB::table('en_country')->get();
        $data['customer'] = TubCustomers_provider::valid()->find($id);
        return view('provider.eastWest.ourCustomers.update', $data);
    }

    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'name'       => 'required',
            'country_id' => 'required',
            'image'      => 'required'
        ]);

        $imageData = $request->thumbnail;

        if ($validator->passes()) {
            TubCustomers_provider::valid()->find($id)->update([
                'name'       => $request->name,
                'country_id' => $request->country_id,
                "image"      => $request->image
            ]);
            // TubCustomers_provider::create($input);
            $output['messege'] = 'Client has been updated';
            $output['msgType'] = 'success';
        } else {
            $output = Helper::vError($validator);
        }
        
        echo json_encode($output);
    }

    public function destroy($id)
    {
        TubCustomers_provider::valid()->find($id)->delete();
    }

    public function countrySorting (Request $request){
        // $data['teams'] = TubManagementTeam_provider::valid()->orderBy('sl_no')->get();
        $data['customerWiseCountries'] = TubCustomers_provider::join('en_country', 'en_country.id', '=', 'tub_customers.country_id')
            ->select('tub_customers.id', 'tub_customers.country_id', 'en_country.name as country_name', 'en_country.iso')
            ->groupBy('tub_customers.country_id')
            ->get();
        
        return view('provider.eastWest.ourCustomers.countrySorting', $data);
    }
    public function countrySortingAction(Request $request){
        $data = $request->all();
        // dd($data);
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            DB::table('en_country')->where('id',$sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

        $output['messege'] = 'Country sorting successfully';
        $output['msgType'] = 'success';
        
        echo json_encode($output); 
    }

    public function companySorting (Request $request){
        $data['customerWiseCountries'] = TubCustomers_provider::join('en_country', 'en_country.id', '=', 'tub_customers.country_id')
            ->select('tub_customers.id', 'tub_customers.country_id', 'en_country.name as country_name')
            ->groupBy('tub_customers.country_id')
            ->get();
            
        return view('provider.eastWest.ourCustomers.companySorting', $data);
    }
    public function countryWiseCompany (Request $request){
        $data['customerWiseCountries'] = TubCustomers_provider::select('id', 'country_id', 'image', 'name')
            ->where('country_id', $request->country_id)
            ->valid()
            ->get();
            
        return view('provider.eastWest.ourCustomers.countryWiseCompany', $data);
    }
    public function companySortingAction(Request $request){
        $data = $request->all();
        
        $sorted = $request->sorted;
        $i=1;
        foreach($sorted as $sortId) {
            TubCustomers_provider::where('id',$sortId)->update([
                'sl_no' => $i
            ]);
            $i++;
        }

        $output['messege'] = 'Company sorting successfully';
        $output['msgType'] = 'success';
        
        echo json_encode($output); 
    }
}
