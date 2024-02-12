<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminSubMenu extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = array('id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_sub_menu';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

}
