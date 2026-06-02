<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group_task';

    protected $fillable = [
      'id',
      'name',
      'icon',
      'invite_code',
      'description',
      'ispublic',
    ];

    public $timestamps = false;
}
