<?php

namespace App\Model;
use DB;
use Auth;

class EnProfileEducation extends BaseModel
{
    protected $table = 'en_users_education';

    protected $guarded = array('id', 'created_at', 'updated_at');
    
    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}