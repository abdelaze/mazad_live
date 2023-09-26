<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $table="admins";
    protected $guard = 'admin';
    protected $guarded = ['id'];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
