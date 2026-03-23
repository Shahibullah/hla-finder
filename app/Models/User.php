<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role',
        'name',
        'dob',
        'sex',
        'phone_no',
        'email',
        'password',
        'status',
        'address_by_divisions',
        'hla_type',
        'hla_class',
        'lab_name',
        'lab_address',
        'theme',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}