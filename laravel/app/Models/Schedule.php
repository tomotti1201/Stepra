<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'task_id',
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

    protected $casts = [
        'scheduled_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeMonth($query, $year, $month)
    {
        return $query
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month);
    }

    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween(
            'scheduled_date',
            [$start, $end]
        );
    }
}