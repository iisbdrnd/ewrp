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

    // public static function corporateAccountAccessLinks($account_id, $module_id)
    // {
    //     $links = EnCorporateAccountAccess_provider::join('software_internal_link', 'en_corporate_account_access.link_id', '=', 'software_internal_link.id')
    //         ->join('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
    //         ->select('en_corporate_account_access.*')
    //         ->where('software_menu.module_id', $module_id)
    //         ->where('en_corporate_account_access.account_id', $account_id)
    //         ->where('en_corporate_account_access.type', 2)
    //         ->where('software_menu.valid', 1)
    //         ->where('software_internal_link.valid', 1)
    //         ->where('en_corporate_account_access.valid', 1)
    //         ->get();
    //     return $links;
    // }

    // public static function corporateUserAccessLinks($user_id, $module_id)
    // {
    //     $links = EnCorporateUserAccess_corporate::join('software_internal_link', 'en_corporate_user_access.link_id', '=', 'software_internal_link.id')
    //         ->join('software_menu', 'software_internal_link.menu_id', '=', 'software_menu.id')
    //         ->select('en_corporate_user_access.*')
    //         ->where('software_menu.module_id', $module_id)
    //         ->where('en_corporate_user_access.user_id', $user_id)
    //         ->where('en_corporate_user_access.type', 2)
    //         ->where('software_menu.valid', 1)
    //         ->where('software_internal_link.valid', 1)
    //         ->where('en_corporate_user_access.valid', 1)
    //         ->get();
    //     return $links;
    // }

}
