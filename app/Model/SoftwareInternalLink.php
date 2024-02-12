<?php

namespace App\Model;

class SoftwareInternalLink extends BaseModel
{
    protected $table = 'software_internal_link';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid', 'module_name');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public static function projectAccessLinks($project_id, $module_id)
    {
        $links = ProjectAccess::join('software_internal_link', 'project_access.link_id', '=', 'software_internal_link.id')
            ->join('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
            ->select('project_access.*')
            ->where('software_menu.module_id', $module_id)
            ->where('project_access.project_id', $project_id)
            ->where('project_access.type', 2)
            ->where('software_menu.valid', 1)
            ->where('software_internal_link.valid', 1)
            ->where('project_access.valid', 1)
            ->get();
        return $links;
    }

    public static function packageAccessLinks($package_id, $module_id)
    {
        $links = SoftwarePackageAccess::join('software_internal_link', 'software_package_access.link_id', '=', 'software_internal_link.id')
            ->join('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
            ->select('software_package_access.*')
            ->where('software_menu.module_id', $module_id)
            ->where('software_package_access.package_id', $package_id)
            ->where('software_package_access.type', 2)
            ->where('software_menu.valid', 1)
            ->where('software_internal_link.valid', 1)
            ->where('software_package_access.valid', 1)
            ->get();
        return $links;
    }

}
