<?php

namespace App\Model;

class EwCollectableAccountHeads extends BaseModel
{
    protected $table = 'ew_collectable_account_heads';

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
