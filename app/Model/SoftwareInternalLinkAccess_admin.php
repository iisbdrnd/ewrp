<?php

namespace App\Model;

class SoftwareInternalLinkAccess_admin extends BaseModel
{
    protected $table = 'software_internal_link_access';

    protected $guarded = array('id', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::adminBoot(1);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
