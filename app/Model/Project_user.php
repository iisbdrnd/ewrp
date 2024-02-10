<?php

namespace App\Model;

class Project_user extends BaseModel
{
    protected $table = 'project';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::userBoot(2);
    }

    public function scopeValid($query)
    {
        $project_id = Auth::user()->get()->project_id;
        return $query->where('project_id', $project_id)->where('valid', 1);
    }
}
