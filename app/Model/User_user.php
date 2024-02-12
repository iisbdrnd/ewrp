<?php

namespace App\Model;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User_user extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $hidden = ['password', 'remember_token'];
    protected $guarded = array('id', 'remember_token', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::userBoot(2);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeValid($query)
    {
        $project_id = Auth::user()->get()->project_id;
        return $query->where('project_id', $project_id)->where('valid', 1);
    }
}
