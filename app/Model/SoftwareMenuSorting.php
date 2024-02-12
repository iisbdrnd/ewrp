<?php

namespace App\Model;
use Auth;

class SoftwareMenuSorting extends BaseModel
{
    protected $table = 'software_menu_sorting';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::userBoot();
    }

    public function scopeValid($query)
    {
        $project_id = Auth::user()->get()->project_id;
        
        return $query->where('project_id', $project_id)->where('valid', 1);
    }

}
