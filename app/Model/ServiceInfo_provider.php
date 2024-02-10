<?php

namespace App\Model;
use App\Model\BaseModel;

class ServiceInfo_provider extends BaseModel{
    
    protected $table = 'request_for_information';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'valid', 'logo');

    public static function boot()
    {
        parent::providerBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}