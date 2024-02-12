<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;
use Validator;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DatabaseBackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function databaseBackup(Request $request)
    {
        $data['inputData'] = $request->all();
        return view('admin.databaseBackup.index', $data);
    }

    public function databaseBackupAction(Request $request)
    {
        if (password_verify($request->password, Auth::user()->get()->password)) {
            $date = new DateTime();
            $db_name = DB::connection()->getDatabaseName().'_'.$date->format('YmdHis');
            shell_exec("php artisan backup:mysql-dump ".$db_name);

            $output['url'] = url('storage/app/backups/'.$db_name.'.sql');
            $output['messege'] = 'Backup is completed.';
            $output['msgType'] = 'success';
        } else {
            $output['messege'] = 'Password is wrong.';
            $output['msgType'] = 'danger';
        }
        echo json_encode($output);
    }


}
