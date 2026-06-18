<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class HomeController extends Controller
{
    public function todayTasks(Request $request)
    {
        $userId = $request->query('user_id');

        // 今日の曜日（1=月 ... 7=日）
        $todayWeekDay = date('N');

        $tasks = Task::where('user_id', $userId)
            ->where('status', 'active')
            ->get()
            ->filter(function ($task) use ($todayWeekDay) {

                $weekDays = json_decode($task->week_days, true) ?? [];

                return in_array($todayWeekDay, $weekDays);
            })
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'start_time' => $task->start_time,
                    'required_minutes' => $task->required_minutes,
                    'priority' => $task->priority,
                    'color' => $task->color,
                ];
            })
            ->values();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }
}