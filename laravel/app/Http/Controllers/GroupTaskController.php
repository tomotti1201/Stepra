<?php

namespace App\Http\Controllers;

use App\Models\Grouptask;
use Illuminate\Http\Request;

class GroupTaskController extends Controller
{
    
     //グループタスク一覧取得
    public function index()
    {
        $tasks = Grouptask::all();

        return response()->json($tasks);
    }

    
     //グループタスク作成
     
    public function store(Request $request)
    {
        $task = Grouptask::create([
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
        $task = Grouptask::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}