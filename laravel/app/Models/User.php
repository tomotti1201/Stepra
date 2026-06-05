<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'birth_date',
        'password',
        'icon',
        'profile_text',
        'notification_enabled',
        'theme_color',
        'level',
        'xp',
        'streak',
        'created_at'
    ];

    protected $hidden = [
        'password'
    ];
}