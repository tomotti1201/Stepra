<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupmember extends Model
{
    use HasFactory;

    protected $table = 'group_members';

    protected $fillable = [
      'id',
      'group_id',
      'user_id',
      'notification_enabled',
      'role',
    ];

    public $timestamps = false;
}
