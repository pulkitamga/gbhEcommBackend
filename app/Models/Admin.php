<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable=[
        'full_name','email','password'];
    
    protected $hidden=['password','remember_token',];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
