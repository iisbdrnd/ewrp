<?php

namespace App\Model;
use DB;
use Auth;

class EnPaymentFailed extends BaseModel
{
    protected $table = 'en_payment_failed';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::corporateBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }


}
