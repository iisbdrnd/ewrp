<?php

namespace App\Model;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class EnProviderUser_admin extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'en_provider_user';

    protected $hidden = ['password', 'remember_token'];
    protected $guarded = array('id', 'remember_token', 'created_at', 'created_by', 'created_by_type', 'updated_at', 'updated_by', 'updated_by_type', 'deleted_at', 'deleted_by', 'deleted_by_type', 'valid');

    public static function boot()
    {
        parent::adminBoot(1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeValid($query)
    {
        return $query->where('valid', 1);
    }
}
