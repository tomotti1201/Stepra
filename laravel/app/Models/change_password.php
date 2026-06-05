<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'token',
    'expires_at',
])]
class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';

    public $timestamps = false;

    /**
     * 属性キャスト
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    /**
     * User リレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}