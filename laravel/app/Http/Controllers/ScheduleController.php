<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Task;
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
$tasks = Task::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('status', 'active')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start_date' => $task->start_date,
                    'end_date' => $task->end_date,
                    'week_days' => $task->week_days,
                    'start_time' => $task->start_time,
                    'required_minutes' => $task->required_minutes,
                    'color' => $task->color,
                    'priority' => $task->priority,
                ];
            });
        

        return response()->json([
            'status' => 'success',
            'schedules' => $schedules,
            'tasks' => $tasks
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
    public function getDailySchedules(Request $request)
{
    $date = $request->query('date');
    $userId = $request->query('user_id', Auth::id());

    if (!$date) {
        return response()->json([
            'status' => 'error',
            'message' => 'date is required'
        ], 400);
    }

    // まずScheduleを取得
    $schedules = Schedule::whereDate('scheduled_date', $date)
        ->when($userId, fn($q) => $q->where('user_id', $userId))
        ->orderBy('start_time')
        ->get();

    // Scheduleが存在すればそれを返す
    if ($schedules->isNotEmpty()) {

        return response()->json([
            'status' => 'success',
            'date' => $date,
            'schedules' => $schedules->map(function ($s) {

                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'content' => $s->content,
                    'start_time' => $s->start_time,
                    'required_minutes' => $s->required_minutes,
                    'color' => $s->color,
                    'status' => $s->status,
                    'scheduled_date' => $s->scheduled_date->format('Y-m-d'),
                ];
            })
        ]);
    }

    // Scheduleが無ければTaskから生成
    $week = date('N', strtotime($date));

    $tasks = Task::where('user_id', $userId)
    ->whereDate('start_date', '<=', $date)
    ->where(function ($q) use ($date) {

        $q->whereNull('end_date')
          ->orWhereDate('end_date', '>=', $date);

    })
        ->get()
        ->filter(function ($task) use ($week) {

            $days = json_decode($task->week_days, true);

            return in_array($week, $days ?? []);
        })
        ->map(function ($task) use ($date) {

            return [
                'id' => null,
                'task_id' => $task->id,
                'title' => $task->title,
                'content' => $task->content,
                'start_time' => $task->start_time,
                'required_minutes' => $task->required_minutes,
                'color' => $task->color,
                'status' => 'active',
                'scheduled_date' => $date,
            ];
        })
        ->values();

    return response()->json([
        'status' => 'success',
        'date' => $date,
        'schedules' => $tasks
    ]);
}
    public function detail(Request $request)
    {
        return view('scheduleDetail');
    }
}