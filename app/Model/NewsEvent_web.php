<?php

namespace App\Model;
use DB;
use Auth;

class NewsEvent_web extends BaseModel 
{
    protected $table = 'news_event';

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }

}
