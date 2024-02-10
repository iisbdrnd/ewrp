<?php

namespace App\Model;

class SoftwareMenuAccess_corporate extends BaseModel
{
    protected $table = 'software_menu_access';

    protected $guarded = array('id', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::corporateBoot(3);
    }

    public function scopeValid($query)
    {
        $project_id = Auth::corporate()->get()->project_id;
        return $query->where('project_id', $project_id)->where('valid', 1);
    }
}