<?php

namespace App\Model;
use Auth;
use App\Model\BaseModel;

class EnProviderUserInfo_admin extends BaseModel
{
    protected $table = 'en_provider_user_info';

    protected $guarded = array('id', 'surname', 'job_area', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::adminBoot(1);
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
