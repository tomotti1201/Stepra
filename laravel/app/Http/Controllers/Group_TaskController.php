<?php

namespace App\Http\Controllers;

use App\Models\Group_task;
use Illuminate\Http\Request;

class GroupTaskController extends Controller
{
    
     //グループタスク一覧取得
    public function index()
    {
        $tasks = Group_task::all();

        return response()->json($tasks);
    }

    
     //グループタスク作成
     
    public function store(Request $request)
    {
        $task = Group_task::create([
            'group_id' => $request->group_id,
            'title' => $request->title,
            'content' => $request->content,
            'created_by' => $request->created_by,
        ]);

        return response()->json($task, 201);
    }

    
     //グループタスク削除
     
    public function destroy($id)
    {
        $task = Group_task::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}