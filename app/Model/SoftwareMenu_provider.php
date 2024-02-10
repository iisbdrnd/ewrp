<?php

namespace App\Model;
use DB;
use Auth;

use App\Model\EnProviderUser_provider;
use App\Model\SoftwareMenuAccess_provider;
use App\Model\EnUsers_corporate;
use App\Model\EnCorporateUserAccess_corporate;
use App\Model\ProjectAccess_provider;
use App\Model\EnCorporateAccountAccess_provider;
use App\Model\EnClassroomAccountAccess_provider;
use App\Model\EnClassroomUserAccess_classroom;


class SoftwareMenu_provider extends BaseModel
{
    protected $table = 'software_menu';

    protected $guarded = array('id', 'parent_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::providerBoot();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function providerUserAccessMenus($user_id, $module_id)
    {
        $project_id = EnProviderUser_provider::find($user_id)->project_id;

      
        $menus = DB::table('software_menu_access')->select('software_menu.*', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.parent_id, software_menu.parent_id) as parent_id"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.sl_no, software_menu.sl_no) as sl_no"))
            ->join('software_menu', function($join) use ($module_id){
                $join->on('software_menu_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.module_id','=', DB::raw($module_id))
                    ->on('software_menu.status', '=', DB::raw(1))
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_menu_sorting', function($join) use ($project_id){
                $join->on('software_menu_access.menu_id', '=', 'software_menu_sorting.menu_id')
                    ->on('software_menu_sorting.project_id','=', DB::raw($project_id))
                    ->on('software_menu_sorting.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->where('software_menu_access.user_id', $user_id)
            ->where('software_menu_access.valid', 1)
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }

    public static function corporateUserAccessMenus($user_id, $module_id)
    {
        $project_id = EnUsers_corporate::find($user_id)->project_id;

        $menus = EnCorporateUserAccess_corporate::select('software_menu.*', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.parent_id, software_menu.parent_id) as parent_id"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.sl_no, software_menu.sl_no) as sl_no"))
            ->join('software_menu', function($join) use ($module_id){
                $join->on('en_corporate_user_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.module_id','=', DB::raw($module_id))
                    ->on('software_menu.status', '=', DB::raw(1))
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_menu_sorting', function($join) use ($project_id){
                $join->on('en_corporate_user_access.menu_id', '=', 'software_menu_sorting.menu_id')
                    ->on('software_menu_sorting.project_id','=', DB::raw($project_id))
                    ->on('software_menu_sorting.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->where('en_corporate_user_access.user_id', $user_id)
            ->where('en_corporate_user_access.type', 1)
            ->where('en_corporate_user_access.valid', 1)
            ->groupBy('id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }

    public static function projectAccessMenus($project_id, $module_id)
    {
        $menus = ProjectAccess_provider::join('software_menu', function($join) use ($module_id){
            $join->on('project_access.menu_id', '=', 'software_menu.id')
                ->on('software_menu.module_id','=', DB::raw($module_id))
                ->on('software_menu.status', '=', DB::raw(1))
                ->on('software_menu.valid', '=', DB::raw(1));
        })
            ->leftJoin('software_menu_sorting', function($join) use ($project_id){
                $join->on('project_access.menu_id', '=', 'software_menu_sorting.menu_id')
                    ->on('software_menu_sorting.project_id','=', DB::raw($project_id))
                    ->on('software_menu_sorting.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_menu.*', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.parent_id, software_menu.parent_id) as parent_id"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.sl_no, software_menu.sl_no) as sl_no"))
            ->where('project_access.project_id', $project_id)
            ->where('project_access.type', 1)
            ->where('project_access.valid', 1)
            ->groupBy('project_access.menu_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }

    public static function corporateAccountAccessMenus($account_id, $module_id)
    {
        $menus = EnCorporateAccountAccess_provider::join('software_menu', function($join) use ($module_id){
            $join->on('en_corporate_account_access.menu_id', '=', 'software_menu.id')
                ->on('software_menu.module_id','=', DB::raw($module_id))
                ->on('software_menu.status', '=', DB::raw(1))
                ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_menu.*', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), "software_menu.parent_id", "software_menu.sl_no")
            ->where('en_corporate_account_access.account_id', $account_id)
            ->where('en_corporate_account_access.type', 1)
            ->where('en_corporate_account_access.valid', 1)
            ->groupBy('en_corporate_account_access.menu_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }



    //For Classroom Access
    public static function classroomAccountAccessMenus($account_id, $module_id)
    {
        $menus = EnClassroomAccountAccess_provider::join('software_menu', function($join) use ($module_id){
            $join->on('en_classroom_account_access.menu_id', '=', 'software_menu.id')
                ->on('software_menu.module_id','=', DB::raw($module_id))
                ->on('software_menu.status', '=', DB::raw(1))
                ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_menu.*', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), "software_menu.parent_id", "software_menu.sl_no")
            ->where('en_classroom_account_access.account_id', $account_id)
            ->where('en_classroom_account_access.type', 1)
            ->where('en_classroom_account_access.valid', 1)
            ->groupBy('en_classroom_account_access.menu_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }


    public static function classroomUserAccessMenus($user_id, $module_id)
    {
        $project_id = EnUsers_classroom::find($user_id)->project_id;

        $menus = EnClassroomUserAccess_classroom::select('software_menu.*', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.parent_id, software_menu.parent_id) as parent_id"), DB::raw("if(software_menu_sorting.id>0, software_menu_sorting.sl_no, software_menu.sl_no) as sl_no"))
            ->join('software_menu', function($join) use ($module_id){
                $join->on('en_classroom_user_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.module_id','=', DB::raw($module_id))
                    ->on('software_menu.status', '=', DB::raw(1))
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_menu_sorting', function($join) use ($project_id){
                $join->on('en_classroom_user_access.menu_id', '=', 'software_menu_sorting.menu_id')
                    ->on('software_menu_sorting.project_id','=', DB::raw($project_id))
                    ->on('software_menu_sorting.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->where('en_classroom_user_access.user_id', $user_id)
            ->where('en_classroom_user_access.type', 1)
            ->where('en_classroom_user_access.valid', 1)
            ->groupBy('id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }

}
