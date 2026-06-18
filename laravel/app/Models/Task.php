<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tasks';

    protected $fillable = [
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
        'status'

    ];

}
?>

