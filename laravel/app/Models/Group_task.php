<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group_task extends Model
{
    use HasFactory;

    protected $table = 'group_tasks';

    protected $fillable = [
        'group_id',
        'title',
        'content',
        'created_by',
    ];

    public $timestamps = false;
}