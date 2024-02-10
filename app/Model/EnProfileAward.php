<?php

namespace App\Model;
use DB;
use Auth;

class EnProfileAward extends BaseModel
{
    protected $table = 'en_users_award';

    protected $guarded = array('id', 'created_at', 'updated_at');
    
    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}