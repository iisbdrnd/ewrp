<?php

namespace App\Model;
use DB;
use Auth;

class JobOpeningAttachment_provider extends BaseModel
{
    protected $table = 'job_opening_attachments';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::providerBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}