<?php

namespace App\Model;
use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TubClient_webAuth extends Authenticatable
{
    use Notifiable;

    protected $table = 'tub_client';

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
