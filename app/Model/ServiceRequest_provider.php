<?php

namespace App\Model;
use Auth;
class ServiceRequest_provider extends BaseModel
{
    protected $table = 'service_request';

    protected $guarded = array('id', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::providerBoot(2);
    }

    public function scopeValid($query)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        return $query->where('project_id', $project_id)->where('valid', 1);
    }

}
