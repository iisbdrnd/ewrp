<?php

namespace App\Model;

use DB;

class AdminMenu extends BaseModel{
	
    protected $table = 'admin_menu';

    protected $guarded = array('id', 'parent_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

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

    public static function adminAccessMenus($admin_id)
    {
        $menus = AdminMenuAccess::select('admin_menu.*', DB::raw("if(admin_menu.resource=1, concat('softAdmin.softAdmin.', admin_menu.route), concat('softAdmin.', admin_menu.route)) as route"))
            ->join('admin_menu', 'admin_menu_access.menu_id', '=', 'admin_menu.id')
            ->where('admin_menu_access.admin_id', $admin_id)
            ->where('admin_menu.valid', 1)
            ->where('admin_menu_access.valid', 1)
            ->orderBy('sl_no', 'asc')
            ->get();
        return $menus;
    }

}
