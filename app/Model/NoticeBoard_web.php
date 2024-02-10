<?php

namespace App\Model;
use DB;
use Auth;

class NoticeBoard_web extends BaseModel
{
    protected $table = 'notice_board';

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
