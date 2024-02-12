<?php

namespace App\Model;
use Auth;

class JobArea_admin extends BaseModel
{
    protected $table = 'job_area';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::adminBoot(1);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}