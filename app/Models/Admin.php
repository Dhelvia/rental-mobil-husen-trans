<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'nama','email','password','no_hp','foto'
    ];

    protected $hidden = ['password'];
}
