<?php

namespace App\Model;
use DB;

class SoftwareModules extends BaseModel
{
    protected $table = 'software_modules';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

    public static function userAccessModules($user_id)
    {
        $modules = SoftwareMenuAccess_user::select('software_modules.*')
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
            ->groupBy('software_modules.id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $modules;
    }

    public static function projectAccessModules($project_id)
    {
        $modules = ProjectAccess::join('software_menu', function($join) {
                $join->on('project_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_modules.*')
            ->where('project_access.project_id', $project_id)
            ->where('project_access.type', 1)
            ->where('project_access.valid', 1)
            ->groupBy('software_menu.module_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $modules;
    }

    public static function packageAccessModules($package_id)
    {
        $modules = SoftwarePackageAccess::join('software_menu', function($join) {
                $join->on('software_package_access.menu_id', '=', 'software_menu.id')
                    ->on('software_menu.valid', '=', DB::raw(1));
            })
            ->leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->select('software_modules.*')
            ->where('software_package_access.package_id', $package_id)
            ->where('software_package_access.type', 1)
            ->where('software_package_access.valid', 1)
            ->groupBy('software_menu.module_id')
            ->orderBy('sl_no', 'asc')
            ->get();
        return $modules;
    }

}
