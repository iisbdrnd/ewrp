<?php

namespace App\Http\Controllers\softAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Auth;
use Helper;
use Validator;
 
use App\Http\Requests;
use App\Model\Admin;

class AdminController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('softAdmin.admin.list', $data);
    }

    public function adminListData(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['name', 'username', 'email']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['admin'] = Admin::valid() 
            ->where(function($query) use ($search)
            {
                $query->where('name', 'LIKE', '%'.$search.'%')
                 ->orWhere('username', 'LIKE', '%'.$search.'%')
                 ->orWhere('email', 'LIKE', '%'.$search.'%');
            })
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.admin.listData', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['inputData'] = $request->all();
        
        return view('softAdmin.admin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $output = array();
        
        $validator = Validator::make($request->all(), [
            "name"          => "required",
            "email"         => "required|email",
            "username"      => "required",
            "password"      => "required|min:6|confirmed"
        ]);

        if ($validator->passes()) {
            $emailCheck = Admin::where('email', $request->email)->where('valid', 1)->first();
            $usernameCheck = Admin::where('username', $request->username)->where('valid', 1)->first();
            if (empty($emailCheck && $usernameCheck)) {
                if (empty($emailCheck)) {
                    if (empty($usernameCheck)) {
                        Admin::create([
                             "name"         => $request->name,
                             "email"        => $request->email,
                             "username"     => $request->username,
                             "password"     => bcrypt($request->password),
                             "status"       => "Active"
                            ]);

                            $output['messege'] = 'Admin has been created';
                            $output['msgType'] = 'success';
                    }else {
                    $output['messege'] = 'Username already exist.';
                    $output['msgType'] = 'danger';
                }
                    
                }else {
                    $output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                }
            }else {
                $output['messege'] = 'Email & Username already exist.';
                $output['msgType'] = 'danger';
            }
             
            
            } else {
            $output = Helper::vError($validator);
        }

        echo json_encode($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['admin'] = Admin::valid()->find($id);

        return view('softAdmin.admin.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $output = array(); 

        $validator = Validator::make($request->all(), [
            "name"          => "required",
            "email"         => "required|email",
            "username"      => "required"
        ]);

        if ($validator->passes()) {
            $emailCheck = Admin::where('email', $request->email)->where('id', '!=', $id)->first();
            $usernameCheck = Admin::where('username', $request->username)->where('id', '!=', $id)->first();
            
            if (empty($emailCheck && $usernameCheck)) {
                if (empty($emailCheck)) {
                    if (empty($usernameCheck)) {
                        Admin::valid()->find($id)->update([
                            "name"         => $request->name,
                            "email"        => $request->email,
                            "username"     => $request->username
                        ]);
                        if(!empty($request->password)) {
                             $pass_input["password"] = bcrypt($request->password);
                             Admin::valid()->where('id', $id)->update($pass_input);
                        }
                        $output['messege'] = 'Admin has been updated';
                        $output['msgType'] = 'success';
                    }else {
                        $output['messege'] = 'Username already exist.';
                        $output['msgType'] = 'danger';
                    }
                    
                }else {
                    $output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                }
                
            }else {
                $output['messege'] = 'Email & Username already exist.';
                $output['msgType'] = 'danger';
            }
            
            } else {
                $output = Helper::vError($validator);
            }
        
        echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Admin::valid()->find($id)->delete();
    }
}