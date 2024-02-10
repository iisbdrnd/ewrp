<?php

namespace App\Model;

class ProjectInfo extends BaseModel
{
    protected $table = 'project_info';

    protected $guarded = array('id', 'created_at', 'city', 'state', 'post_code', 'office_phone', 'fax', 'website', 'address', 'street', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}
