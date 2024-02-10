<?php

namespace App\Model;
use Auth;

class TubClient_web extends BaseModel
{
    protected $table = 'tub_client';

    protected $guarded = array('id', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    // public static function boot()
    // {
    //     parent::corporateBoot(3);
    // }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
