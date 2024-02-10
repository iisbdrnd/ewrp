<?php

namespace App\Model;
use DB;
use Auth;

class FacilityHeadOfficeGallery_web extends BaseModel 
{
    protected $table = 'facility_head_office_gallery';

    protected $guarded = array('id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid');

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
