<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group_task';

    protected $fillable = [
   'group_id',
        'title',
        'content',
        'created_by',
    ];
    
    public $timestamps = false;
}
