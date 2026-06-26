<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request)
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

        $period = $request->period;

        if (
            !is_null($period) &&
            !in_array($period, ['weekly', 'monthly', 'yearly'])
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '繰り返し設定が不正です'
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

        if ($startDate === '') {
            return response()->json([
                'status' => 'error',
                'message' => '開始日を入力してください'
            ], 400);
        }

        $today = date('Y-m-d');

        if ($startDate < $today) {
            return response()->json([
                'status' => 'error',
                'message' => '開始日は今日以降を指定してください'
            ], 400);
        }

        if (
            $endDate !== '' &&
            $endDate < $startDate
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '終了日は開始日以降を指定してください'
            ], 400);
        }
        
        $endDate = $endDate === ''
            ? null
            : $endDate;

        $requiredMinutes =
            ((int)$duration_hours * 60) +
            (int)$duration_minutes;

        if (
            $requiredMinutes <= 0
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '所要時間を入力してください'
            ], 400);
        }

        $priority = match ($request->priority) {
            '高' => 'high',
            '中' => 'middle',
            '低' => 'low',
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
            'title' => $request->title,
            'content' => $request->content,
            'week_days' => json_encode($weekDays),
            'start_time' => $start_time,
            'required_minutes' => $requiredMinutes,
            'priority' => $priority,
            'color' => $request->color,
            'period' => $period,
            'notification_enabled' => '1',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active'
        ]);

        $start = Carbon::parse($startDate);
$endLimit = (clone $start)->addMonth();

$date = $start->copy();

while ($date->lte($endLimit)) {

    $dow = $date->dayOfWeekIso; // 1(月)〜7(日)

    if (in_array($dow, $weekDays)) {

        Schedule::create([
    'task_id' => $task->id,
    'user_id' => $request->user_id ?? Auth::id(),
    'scheduled_date' => $date->format('Y-m-d'),

    'title' => $task->title,
    'content' => $task->content,
    'week_days' => $task->week_days,
    'start_time' => $task->start_time,
    'required_minutes' => $task->required_minutes,
    'priority' => $task->priority,
    'color' => $task->color,
    'period' => $task->period,
    'notification_enabled' => $task->notification_enabled,
    'start_date' => $task->start_date,
    'end_date' => $task->end_date,
    'status' => $task->status,
]);
    }

    // ⭐⭐⭐これが絶対必要（進める）
    $date->addDay();
}

        return response()->json([
            'status' => 'success',
            'message' => '目標を登録しました',
            'task' => $task
        ], 201);
    }

    public function index()
    {
        $tasks = Task::all();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'タスクが見つかりません'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'task' => $task
        ]);
    }

    public function taskedit($id)
    {
        return Task::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'タスクが見つかりません'
            ], 404);
        }

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

        $period = $request->period;

        if (
            !is_null($period) &&
            !in_array($period, ['weekly', 'monthly', 'yearly'])
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '繰り返し設定が不正です'
            ], 400);
        }
        $start_time = $request->start_time ?? '';

        $duration_hours = (int)$request->duration_hours;
        $duration_minutes = (int)$request->duration_minutes;

        $requiredMinutes = ($duration_hours * 60) + $duration_minutes;

        if (
            $requiredMinutes <= 0
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '所要時間を入力してください'
            ], 400);
        }

        $priority = match ($request->priority) {
            '高' => 'high',
            '中' => 'middle',
            '低' => 'low',
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
            fn($d) => $weekDayMap[$d],
            $weekDays
        );

        $startDate = trim($request->start_date ?? '');
        $endDate = trim($request->end_date ?? '');
        $endDate = $endDate === '' ? null : $endDate;
        if ($startDate === '') {
            return response()->json([
                'status' => 'error',
                'message' => '開始日を入力してください'
            ], 400);
        }

        if (
            !empty($endDate) &&
            strtotime($endDate) < strtotime($startDate)
        ) {
            return response()->json([
                'status' => 'error',
                'message' => '終了日は開始日以降を指定してください'
            ], 400);
        }
        
        $task->update([
            'title' => $title,
            'week_days' => json_encode($weekDays),
            'period' => $request->period,
            'start_time' => $start_time,
            'required_minutes' => $requiredMinutes,
            'priority' => $priority,
            'color' => $request->color,
            'start_date' => $task->start_date,
            'end_date' => $endDate,
        ]);

        $today = now()->toDateString();

        Schedule::where('task_id', $task->id)
            ->where('scheduled_date', '>=', $today)
            ->delete();

            $start = Carbon::today();
            $endLimit = (clone $start)->addMonth();

            $date = $start->copy();

            while ($date->lte($endLimit)) {

                $dow = $date->dayOfWeekIso;

                if (in_array($dow, $weekDays)) {

                    Schedule::create([
                        'task_id' => $task->id,
                        'user_id' => $task->user_id,
                        'scheduled_date' => $date->format('Y-m-d'),

                        'title' => $title,
                        'content' => $request->content,
                        'week_days' => json_encode($weekDays),
                        'start_time' => $start_time,
                        'required_minutes' => $requiredMinutes,
                        'priority' => $priority,
                        'color' => $request->color,
                        'period' => $request->period,
                        'notification_enabled' => 1,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => 'active'
                    ]);
                }

                $date->addDay();
            }
        return response()->json([
            'status' => 'success',
            'message' => '更新しました',
            'task' => $task
        ]);
    }
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'タスクが見つかりません'
            ], 404);
        }

        Schedule::where('task_id', $task->id)->delete();

        $task->delete();
        return response()->json([
            'status' => 'success',
            'message' => '削除しました'
        ]);
    }
    public function updateStatus(Request $request, $id)
{
    // スケジュールを取得
    $schedule = Schedule::findOrFail($id);

    // リクエストからステータスを取得
    $status = $request->input('status');

    // ステータスのバリデーション
    if (!in_array($status, ['active', 'completed', 'failed'])) {
        return response()->json([
            'status' => 'error',
            'message' => 'invalid status'
        ], 400);
    }

    // ステータス更新
    $schedule->status = $status;

    // 未達成理由の保存
    if ($status === 'failed') {
        $schedule->content = $request->input('content');
    } else {
        // active・completed のときは理由を消す
        $schedule->content = null;
    }

    // 保存
    $schedule->save();

    return response()->json([
        'status' => 'success',
        'message' => 'ステータスを更新しました',
        'schedule' => $schedule
    ]);
}

}