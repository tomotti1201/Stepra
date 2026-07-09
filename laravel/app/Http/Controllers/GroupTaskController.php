<?php

namespace App\Http\Controllers;

use App\Models\Grouptask;
use Illuminate\Http\Request;

class GroupTaskController extends Controller
{
    // Get group task list.
    public function index(Request $request)
    {
        $query = Grouptask::query();

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        $tasks = $query
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
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

    // Create group task.
    public function store(Request $request)
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
            'status' => 'success',
            'task' => $task,
        ], 201);
    }

    // Delete group task.
    public function destroy($id)
    {
        $task = Grouptask::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
