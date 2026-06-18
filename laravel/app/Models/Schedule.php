<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    /**
     * 一括代入可能なカラム
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'scheduled_date',
    ];

    /**
     * 型キャスト
     * → カレンダー処理・日付比較を楽にする
     */
    protected $casts = [
        'scheduled_date' => 'date',
    ];

    /**
     * タイムスタンプ管理
     * （schedulesテーブルには created_at/updated_at を使わない前提）
     */
    public $timestamps = false;

    /**
     * スケジュール → タスク
     * （カレンダー表示・色・タイトル取得に必須）
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * スコープ：ユーザー別取得
     * （APIで毎回書かないようにする）
     */
    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * スコープ：月間取得
     */
    public function scopeMonth($query, $year, $month)
    {
        return $query
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month);
    }

    /**
     * スコープ：日付範囲取得
     */
    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('scheduled_date', [$start, $end]);
    }
}