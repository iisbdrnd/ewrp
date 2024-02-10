<?php

namespace App\Model;
use App\Model\BaseModel;

class TubSustainability_web extends BaseModel{
    protected $table = 'tub_sustainability';

    // protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    // public static function boot()
    // {
    //     parent::providerBoot();
    // }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}