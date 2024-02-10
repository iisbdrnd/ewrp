<?php

namespace App\Model;
use App\Model\BaseModel;

class ServiceReqApprovedRecord_provider extends BaseModel{
    
    protected $table = 'service_request_approved_records';

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