<?php

namespace App\Model;
use Auth;

class EwMobilization extends BaseModel
{
    protected $table = 'ew_mobilizations';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::userBoot();
    }
    
    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

    public function configuration(){
        return $this->hasOne('App\Model\EwConfiguration');
    }

}
