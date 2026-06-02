<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    //タスクの一覧取得
    public function index()
    {
        $tasks = Task::all();

        return response()->json($tasks);
    }

    //その日のタスクを取得したい
  public function userTask(){
    $userId = Auth::id();
    //$userId = 1;

    if (!$userId) {
        return response()->json([
            'message' => 'ログインしてください',
        ], 401);
    }

    $todayWeekDay = now()->isoWeekday();
    $today = now()->toDateString();

    $tasks = Task::where('user_id', $userId)
        ->whereDate('start_date', '<=', $today)
        ->whereDate('end_date', '>=', $today)
        ->whereRaw('FIND_IN_SET(?, week_days)', [$todayWeekDay])
        ->get();

    return response()->json($tasks);
}

    //タスク作成
    public function store(Request $request)
    {
        $task = Task::create([
            'user_id' => $request -> user_id,
            'title' => $request->title,
            'content' => $request->content,
            'week_days'=>$request->week_days,
          'start_time' => $request->start_time,
            'required_minutes'=>$request->required_minutes,
            'priority'=>$request->priority,
            'color'=>$request->color,
            'period'=>$request->period,
           'notification_enabled' => $request->notification_enabled,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'status'=>$request->status
        ]);
        return response()->json($task,201);
    }

    public function create()
    {
        return redirect('/tasks');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);

        return view('frontend.mokuhyohensyu', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->update($request->all());

        return redirect('/tasks');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return redirect('/tasks');
    }
}