<?php

namespace App\Http\Controllers;

use App\Models\Grouptask;
use Illuminate\Http\Request;

class GroupTaskController extends Controller
{

    /**
     * グループタスク一覧
     */
    public function index(Request $request)
    {
        $query = Grouptask::query();

        if($request->filled('group_id')){

            $query->where(
                'group_id',
                $request->group_id
            );

        }

        if($request->filled('user_id')){

            $query->where(
                'user_id',
                $request->user_id
            );

        }

        $tasks = $query
            ->orderBy('id','desc')
            ->get();

        return response()->json([
            'status'=>'success',
            'tasks'=>$tasks
        ]);
    }


    /**
     * 詳細取得
     */
    public function show($id)
    {
        $task = Grouptask::find($id);

        if(!$task){

            return response()->json([
                'status'=>'error',
                'message'=>'タスクが存在しません'
            ],404);

        }

        return response()->json([
            'status'=>'success',
            'task'=>$task
        ]);
    }


    /**
     * 登録
     */
    public function store(Request $request)
{
    $title = trim($request->title ?? '');
    $weekDays = $request->week_days ?? [];
    $startTime = trim($request->start_time ?? '');
    $startDate = trim($request->start_date ?? '');
    $endDate = trim($request->end_date ?? '');

    // タスク名チェック
    if ($title === '') {
        return response()->json([
            'status' => 'error',
            'message' => 'グループタスク名を入力してください'
        ], 400);
    }

    // 曜日チェック
    if (count($weekDays) === 0) {
        return response()->json([
            'status' => 'error',
            'message' => '曜日を選択してください'
        ], 400);
    }

    // 開始時間チェック
    if ($startTime === '') {
        return response()->json([
            'status' => 'error',
            'message' => '開始時間を入力してください'
        ], 400);
    }


    // 所要時間
    $requiredMinutes =
        (int)($request->required_minutes ?? 0);


    if ($requiredMinutes <= 0) 
    {
        $validated = $request->validate([
            'group_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'week_days' => ['required'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'required_minutes' => ['nullable', 'integer', 'min:1'],
            'priority' => ['nullable', 'string', 'max:20'],
            'color' => ['required', 'string', 'max:20'],
            'period' => ['required', 'string', 'max:20'],
            'notification_enabled' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'max:20'],
            'user_id' => ['nullable', 'integer'],
            'created_by' => ['required', 'integer'],
        ]);

        $weekDays = $validated['week_days'];
        $priority = match ($validated['priority'] ?? 'middle') {
            '高' => 'high',
            '中' => 'middle',
            '低' => 'low',
            default => $validated['priority'] ?? 'middle',
        };
        $period = match ($validated['period']) {
            '毎週' => 'weekly',
            '毎月' => 'monthly',
            '毎年' => 'yearly',
            '自由設定' => 'weekly',
            default => $validated['period'],
        };

        $task = Grouptask::create([
            'group_id' => $validated['group_id'],
            'user_id' => $validated['user_id'] ?? $validated['created_by'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'week_days' => is_array($weekDays)
                ? implode(',', $weekDays)
                : $weekDays,
            'start_time' => $validated['start_time'] ?? null,
            'required_minutes' => $validated['required_minutes'] ?? null,
            'priority' => $priority,
            'color' => $validated['color'],
            'period' => $period,
            'notification_enabled' => $validated['notification_enabled'] ?? 1,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'created_by' => $validated['created_by'],
        ]);

        return response()->json([
            'status' => 'error',
            'message' => '所要時間を入力してください'
        ], 400);

    }


    // 日付チェック
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


    if ($endDate !== '' && $endDate < $startDate) {
        return response()->json([
            'status' => 'error',
            'message' => '終了日は開始日以降を指定してください'
        ], 400);
    }


    $endDate = $endDate ?: null;


    // 優先度変換
    $priority = match ($request->priority) {
        '高' => 'high',
        '中' => 'middle',
        '低' => 'low',
        default => null
    };


    // 曜日を数値化
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


    // 登録
    $task = Grouptask::create([

        'group_id' => $request->group_id,

        'user_id' => $request->user_id,

        'title' => $title,

        'content' => $request->content ?? '',

        'week_days' => json_encode($weekDays),

        'start_time' => $startTime,

        'required_minutes' => $requiredMinutes,

        'priority' => $priority,

        'color' => $request->color ?? '#0d6efd',

        'period' => $request->period ?? 'weekly',

        'notification_enabled' => 1,

        'start_date' => $startDate,

        'end_date' => $endDate,

        'status' => 'active'

    ]);


    return response()->json([

        'status' => 'success',

        'message' => 'グループタスクを登録しました',

        'task' => $task

    ], 201);
}


    /**
     * 削除
     */
    public function destroy($id)
    {
        $task =
            Grouptask::find($id);

        if(!$task){

            return response()->json([
                'status'=>'error',
                'message'=>'タスクが存在しません'
            ],404);

        }

        $task->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'削除しました'
        ]);

    }

}
