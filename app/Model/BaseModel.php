<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class BaseModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    //$type = 1 means Admin Control And 2 means User Control
    public static function adminBoot($type=0)
    {
        if(Auth::admin()->check()) {
            $person_id = Auth::admin()->get()->id;
            self::bootAction($person_id, "", $type);
        }
    }

    //$type = 1 means Admin Control And 2 means User Control
    public static function userBoot($type=0)
    {
        if(Auth::user()->check()) {
            $person_id = Auth::user()->get()->id;
            $project_id = Auth::user()->get()->project_id;
            self::bootAction($person_id, $project_id, $type);
        }
    }

    public static function bootAction($person_id, $project_id, $type)
    {
        parent::boot();

        static::creating(function($model) use ($person_id, $project_id, $type)
        {
            $model->created_by = $person_id;
            $model->updated_by = $person_id;
            if(!empty($project_id)) { $model->project_id = $project_id; }
            if($type>0) { $model->created_by_type = $type; $model->updated_by_type = $type; }
            $model->valid = 1;
        });

        static::updating(function($model) use ($person_id, $type)
        {
            $model->updated_by = $person_id;
            if($type>0) { $model->updated_by_type = $type; }
        });

        static::deleting(function($model) use ($person_id, $type)
        {
            $model->updated_by = $person_id;
            $model->deleted_by = $person_id;
            if($type>0) { $model->updated_by_type = $type; $model->deleted_by_type = $type; }
            $model->valid = 0;
            $model->update();
        });
    }

}
