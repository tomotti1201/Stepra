<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
      'id',
      'name',
      'icon',
      'invite_code',
      'description',
      'is_public',
    ];

    public $timestamps = false;
}
