<?php

namespace App\Model;

class AdminInternalLink extends BaseModel
{
    protected $table = 'admin_internal_link';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
