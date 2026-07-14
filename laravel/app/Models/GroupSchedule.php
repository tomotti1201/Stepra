<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupSchedule extends Model
{
    protected $table = 'group_schedules';

    protected $fillable = [
        'group_task_id',
        'group_id',
        'user_id',
        'scheduled_date',
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
    ];

        public $timestamps = false;

}