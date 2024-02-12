<?php

namespace App\Model;

class ProjectSocialLink extends BaseModel
{
    protected $table = 'project_social_link';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}
