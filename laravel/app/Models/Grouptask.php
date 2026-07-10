<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grouptask extends Model
{
    use HasFactory;

    protected $table = 'group_tasks';

    protected $fillable = [
        'user_id',
        'group_id',
        'title',
        'content',
        'week_days',
        'start_time',
        'required_minutes',
        'priority',
        'color',
        'period',
        'notification_enabled',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    public $timestamps = false;
}
