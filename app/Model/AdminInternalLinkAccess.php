<?php

namespace App\Model;

class AdminInternalLinkAccess extends BaseModel
{
    protected $table = 'admin_internal_link_access';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::adminBoot();
    }

}
