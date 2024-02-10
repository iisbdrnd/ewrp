<?php

namespace App\Model;
use DB;
use Auth;

class EnEmailNotificationConfiguration extends BaseModel
{
    protected $table = 'en_email_notification_configuration';

    protected $guarded = array('id', 'parent_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::providerBoot();
    }

    public function scopeValid($query)
    {
        $project_id = Auth::guard()->user()->project_id;
        return $query->where('project_id', $project_id)->where('valid', 1);
    }


}
