<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * 🔵 通常一覧（必要なら使用）
     * ※今のカレンダーでは基本使わない
     */
    public function index(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);
        $userId = $request->query('user_id', Auth::id());

        $query = Schedule::query()
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month)
            ->orderBy('scheduled_date');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return response()->json([
            'status' => 'success',
            'schedules' => $query->get()
        ]);
    }

    /**
     * 🟢 カレンダー用（月間取得）
     * ★JSの fetchSchedules() と完全対応
     * ★taskリレーション込み（色表示用）
     */
    public function getMonthlySchedules(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);
        $userId = $request->query('user_id', Auth::id());

        $schedules = Schedule::with('task')
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->orderBy('scheduled_date')
            ->get()
            ->map(function ($schedule) {
    return [
        'id' => $schedule->id,
        'task_id' => $schedule->task_id,
        'user_id' => $schedule->user_id,
        'scheduled_date' => $schedule->scheduled_date->format('Y-m-d'),

        'title' => $schedule->title,
        'color' => $schedule->color,
    ];
});

        return response()->json([
            'status' => 'success',
            'schedules' => $schedules
        ]);
    }

    /**
     * 🟡 スケジュール作成
     */
    public function store(Request $request)
    {
        $schedule = Schedule::create([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id ?? Auth::id(),
            'scheduled_date' => $request->scheduled_date,
        ]);

        return response()->json([
            'status' => 'success',
            'schedule' => $schedule
        ], 201);
    }

    /**
     * 🔵 単体取得
     */
    public function show($id)
    {
        $schedule = Schedule::query()->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'schedule' => $schedule
        ]);
    }

    /**
     * 🔴 削除
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'deleted'
        ]);
    }
}