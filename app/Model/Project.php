<?php

namespace App\Model;
use App\Model\BaseModel;

class Project extends BaseModel{
    protected $table = 'project';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'valid', 'logo');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}