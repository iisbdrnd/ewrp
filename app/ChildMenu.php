<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ChildMenu extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
		

    protected $fillable = array('main_menu_id','sub_menu_id','child_menu_name', 'icon', 'link', 'valid');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'child_menu';
    

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    
    

    public function sub()
    {
        return $this->belongsTo('SubMenuModel');
    }
   

}
