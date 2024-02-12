<?php

namespace App\Model;

class EwProjectTrades extends BaseModel
{
    protected $table = 'ew_project_trades';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::userBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}
