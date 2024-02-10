<?php

namespace App\Model;
use DB;
use Auth;

class SoftwareModules extends BaseModel
{
    protected $table = 'software_modules';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        if(Auth::guard('softAdmin')->check()) {
            parent::adminBoot();
        }else{
            parent::providerBoot();
        }
        
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

    public static function providerUserAccessModules($user_id)
    {
        $modules = SoftwareMenuAccess_provider::select('software_modules.*')
            ->join('software_menu', function($join){
                $join->on('software_menu_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.status','=', DB::raw(1))
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->join('software_modules', function($join){
                $join->on('software_menu.module_id', '=', 'software_modules.id')
                    ->on('software_modules.status','=', DB::raw(1))
                    ->on('software_modules.valid', '=', DB::raw(1));
            })
            ->where('software_menu_access.user_id', $user_id)
            ->where('software_menu_access.valid', 1)
            ->where('software_modules.folder_id', 2)
            ->groupBy('software_modules.id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $modules;
    }

    public static function projectAccessModules($project_id){
        if(Auth::guard('softAdmin')->check()) {
            $modules = ProjectAccess::join('software_menu', function($join) {
                $join->on('project_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_modules.*')
            ->where('project_access.project_id', $project_id)
            ->where('project_access.type', 1)
            ->where('project_access.valid', 1)
            ->where('software_modules.folder_id', 2)
            ->groupBy('software_menu.module_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        }else{
            $modules = ProjectAccess_provider::join('software_menu', function($join) {
                $join->on('project_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_modules.*')
            ->where('project_access.project_id', $project_id)
            ->where('project_access.type', 1)
            ->where('project_access.valid', 1)
            ->where('software_modules.folder_id', 2)
            ->groupBy('software_menu.module_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        }
        return $modules;
    }
}
