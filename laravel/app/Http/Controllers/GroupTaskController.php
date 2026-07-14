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

        $tasks = $query
            ->orderBy('id','desc')
            ->get();

        return response()->json([
            'status'=>'success',
            'tasks'=>$tasks
        ]);
    }

    // Get one group task.
    public function show($id)
    {
        $task = Grouptask::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Group task not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'task' => $task,
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
        // Normalize time fields: trim and convert empty string to null
        if ($request->has('start_time')) {
            $start = trim((string)$request->input('start_time'));
            $start = str_replace('：', ':', $start);

            if ($start === '') {
                $request->merge(['start_time' => null]);
            } else {
                $dt = \DateTime::createFromFormat('H:i:s', $start) ?: \DateTime::createFromFormat('H:i', $start);
                if ($dt) {
                    $request->merge(['start_time' => $dt->format('H:i')]);
                } else {
                    $request->merge(['start_time' => $start]);
                }
            }
        }

        $validated = $request->validate([
            'group_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'week_days' => ['required'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'required_minutes' => ['nullable', 'integer', 'min:1'],
            'priority' => ['nullable', 'string', 'max:20'],
            'color' => ['required', 'string', 'max:20'],
            'period' => ['nullable', 'string', 'max:20'],
            'notification_enabled' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'max:20'],
            'user_id' => ['nullable', 'integer'],
            'created_by' => ['required', 'integer'],
        ]);

        $weekDays = $validated['week_days'];

        $task = Grouptask::create([
            'user_id' => $validated['created_by'] ?? null,
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
            'status' => 'success',
            'task' => $task,
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