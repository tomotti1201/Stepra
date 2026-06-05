<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function taskCreate(Request $request)
    {
        $title = trim($request->title ?? '');

        if ($title === '') {
            return response()->json([
                'status' => 'error',
                'message' => '目標名を入力してください'
            ], 400);
        }

        $weekDays = $request->week_days ?? [];

        if (count($weekDays) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => '曜日を選択してください'
            ], 400);
        }

        $start_time = trim($request->start_time ?? '');

        if (empty($start_time)) {
            return response()->json([
                'status' => 'error',
                'message' => '開始タイミングを入力してください'
            ], 400);
        }

                $duration_hours = trim($request->duration_hours ?? '');

        if ($duration_hours === '') {
            return response()->json([
                'status' => 'error',
                'message' => '所要時間を入力してください'
            ], 400);
        }

        $duration_minutes = trim($request->duration_minutes ?? '');

        if ($duration_minutes === '') {
            return response()->json([
                'status' => 'error',
                'message' => '所要時間を入力してください'
            ], 400);
        }

        $startDate = trim($request->start_date ?? '');
        $endDate = trim($request->end_date ?? '');

        if (
            strlen($startDate) !== 8 ||
            strlen($endDate) !== 8
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '開始日を入力してください'
            ], 400);
        }


        $startDate =
            substr($startDate, 0, 4) . '-' .
            substr($startDate, 4, 2) . '-' .
            substr($startDate, 6, 2);

        $endDate =
            substr($endDate, 0, 4) . '-' .
            substr($endDate, 4, 2) . '-' .
            substr($endDate, 6, 2);

        $requiredMinutes =
            ((int)$request->duration_hours * 60) +
            (int)$request->duration_minutes;

        $priority = match ($request->priority) {
            '高' => 'high',
            '中' => 'middle',
            '低' => 'low',
            null => null,
            default => null
        };

        $weekDayMap = [
            '月' => 1,
            '火' => 2,
            '水' => 3,
            '木' => 4,
            '金' => 5,
            '土' => 6,
            '日' => 7
        ];

        $weekDays = array_map(
            fn($day) => $weekDayMap[$day],
            $weekDays
        );

        $task = Task::create([
            'user_id' => $request->user_id,
            'title' => $title,
            'content' => null,
            'week_days' => implode(',', $weekDays),
            'start_time' => $request->start_time,
            'required_minutes' => $requiredMinutes,
            'priority' => $priority,
            'color' => $request->color ?? '#FFAA00',
            'period' => 'weekly',
            'notification_enabled' => 1,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '目標を登録しました',
            'task' => $task
        ], 201);
    }

    public function index()
    {
        $tasks = Task::select(
            'id',
            'title',
            'week_days',
            'start_time',
            'required_minutes'
        )->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }
}