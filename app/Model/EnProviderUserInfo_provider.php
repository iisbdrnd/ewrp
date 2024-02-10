<?php

namespace App\Model;
use Auth;
use App\Model\BaseModel;

class EnProviderUserInfo_provider extends BaseModel
{
    protected $table = 'en_provider_user_info';

    protected $guarded = array('id', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::providerBoot(1);
    }

    public function scopeValid($query)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        return $query->where('project_id', $project_id)->where('valid', 1);
    }

}
