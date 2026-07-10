<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grouptask extends Model
{
    use HasFactory;

    protected $table = 'group_tasks';

    public $timestamps = false;

    protected $fillable = [

        'group_id',
        'user_id',
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
    protected $casts = [

        'notification_enabled' => 'boolean',

        'start_date' => 'date',

        'end_date' => 'date',

    ];

    public function group()
    {
        return $this->belongsTo(
            Group::class,
            'group_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

}