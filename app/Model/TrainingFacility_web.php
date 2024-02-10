<?php

namespace App\Model;
use DB;
use Auth;

class TrainingFacility_web extends BaseModel 
{
    protected $table = 'training_facilities';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
