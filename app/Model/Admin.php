<?php

namespace App\Model;
use Auth;

class Admin extends BaseModel
{
    protected $table = 'admin';

    protected $guarded = array('id', 'created_at', 'deleted_at', 'updated_at', 'remember_token', 'valid');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
