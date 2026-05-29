<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id','title','content','week_days','start_time','required_mminutesa','priority','color','period','notification_enabled','start_date','end_date','status'])]



class Task extends Model{

    use HasFactory;
    protected $table = 'task';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'week_days',
        'start_time',
        'required_mminutesa',
        'priority',
        'color',
        'period',
        'notification_enabled',
        'start_date',
        'end_date',
        'status'

    ];

}