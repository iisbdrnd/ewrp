<?php

namespace App\Model;
use App\Model\BaseModel;

class TubBanner_web extends BaseModel{
    protected $table = 'tub_banner';

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