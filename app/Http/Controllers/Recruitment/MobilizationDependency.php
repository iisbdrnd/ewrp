<?php
namespace App\Http\Controllers\Recruitment;

use App\Http\Controllers\Controller;   
use App\Http\Requests;
use Auth;
use DB;
use Helper;
use DateTime;
use Illuminate\Http\Request;
use Validator;
use App\Model\EwMobilizeDependencyMaster;
use App\Model\EwMobilization;


class MobilizationDependency extends Controller
{

    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('recruitment.mobilizationDependency.list', $data);
    }

    public function mobilizationDependencyData(Request $request) {
        $data           = $request->all();
        $search         = $request->search;
        
        $data['access'] = Helper::userPageAccess($request);
        $ascDesc        = Helper::ascDesc($data, ['country_name', 'updated_at']);
        $paginate       = Helper::paginate($data);
        $data['sn']     = $paginate->serial;

        $data['ewMobilizationsDependency'] = EwMobilizeDependencyMaster::where(function($query) use ($search)
            {
                $query->where('country_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('updated_at', 'LIKE', '%'.$search.'%');
            })
            ->groupBy('project_country_id')
            ->paginate($paginate->perPage);

        return view('recruitment.mobilizationDependency.listData', $data);
    }


    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        $mobilizationDependency = EwMobilizeDependencyMaster::groupBy('project_country_id')->lists('project_country_id');
        $data['countries'] = DB::table('countries')
                               ->where('show_status', 1)
                               ->whereNotIn('id', $mobilizationDependency)
                               ->get();
        $data['mobilization'] = EwMobilization::valid()
            ->get();
        
        return view('recruitment.mobilizationDependency.create', $data);
    }

    public function getMobilizeFilter(Request $request)
    {
        $mobilize_id = array_filter($request->mobilize_get_id);


        if(empty($mobilize_id))
        {
            $mobilize_get_id = array();
        }
        else
        {
           $mobilize_get_id = $mobilize_id;
        }

        $data['mobilizeSearch'] = EwMobilization::valid()
            ->whereNotIn('id', $mobilize_get_id)
            ->get();

        return view('recruitment.mobilizationDependency.mobilizeFilter', $data);
    
    }
    public function getDependencyFilter(Request $request)
    {

        $data['dependencySearch'] = EwMobilization::valid()
            ->get();

        return view('recruitment.mobilizationDependency.dependencyFilter', $data);
    
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $output = array();
        $input = $request->all();
        $mobilization_id = $request->mobilize_name_id;
        $project_country_id = $request->project_country_id;
        $project_country_name = $request->project_contry_name;
        $mobilize_depended_on_id     = $request->mobilize_dependency_id;


        $validator = Validator::make($input, [
            'project_country_id'         => 'required'
        ]);

        if ($validator->passes()) {
            foreach($mobilization_id as $key => $mobilize_id) 
                {
                    EwMobilizeDependencyMaster::create([
                        'project_country_id'     => $project_country_id, 
                        'country_name'           => $project_country_name, 
                        'mobilize_id'            => $mobilize_id, 
                        'mobilize_depended_on_id'=> $mobilize_depended_on_id[$key]
                    ]);
                }

            $output['messege'] = 'Dependency has been Updated';
            $output['msgType'] = 'success'; 
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);

        DB::commit();

    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['mobilization'] = EwMobilizeDependencyMaster::valid()->where('project_country_id',$id)->take(15)->get();
        $data['last_mobilization'] = EwMobilizeDependencyMaster::valid()->where('project_country_id',$id)->skip(15)->take(15)->get();
        $data['project_country_id'] = $id;
        $mobilizationDependency = EwMobilizeDependencyMaster::groupBy('project_country_id')
                                   ->where('project_country_id', '!=', $id)
                                   ->lists('project_country_id');

        $data['countries'] = DB::table('countries')
                               ->where('show_status', 1)
                               ->whereNotIn('id', $mobilizationDependency)
                               ->get();

        $data['all_mobilization'] = EwMobilization::valid()
            ->get();

        return view('recruitment.mobilizationDependency.update', $data);
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $output = array();
        $input = $request->all();
        $mobilization_id = $request->mobilize_name_id;
        $project_country_id = $request->project_country_id;
        $project_country_name = $request->project_contry_name;
        $mobilize_depended_on_id     = $request->mobilize_dependency_id;
        $all_id =EwMobilizeDependencyMaster::valid()->where('project_country_id',$id)->lists('id');


        $validator = Validator::make($input, [
            'project_country_id'         => 'required'
        ]);

        if ($validator->passes()) {
            foreach($all_id as $key => $value) 
            {
                EwMobilizeDependencyMaster::find($value)->update([
                    'project_country_id'     => $project_country_id, 
                    'country_name'           => $project_country_name, 
                    'mobilize_id'            => $mobilization_id[$key], 
                    'mobilize_depended_on_id'=> $mobilize_depended_on_id[$key]
                ]);
            }

            $output['messege'] = 'Dependency has been Updated';
            $output['msgType'] = 'success'; 
        } else {
            $output = Helper::vError($validator);
        }
        echo json_encode($output);

        DB::commit();
    }

    public function destroy($id,Request $request)
    {
        DB::beginTransaction();

        $all_id =EwMobilizeDependencyMaster::valid()->where('project_country_id',$id)->lists('id');
        foreach($all_id as $key => $value) 
                {
                    EwMobilizeDependencyMaster::valid()->find($value)->delete();
                }

        DB::commit();
    }




}
