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

    

    // Create group task.
    public function store(Request $request)
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
            'created_by' => ['required', 'integer'],
        ]);

        $weekDays = $validated['week_days'];

        $priority = $validated['priority'] ?? 'middle';
        $priorityMap = [
            '高' => 'high',
            '中' => 'middle',
            '低' => 'low',
        ];
        if (isset($priorityMap[$priority])) {
            $priority = $priorityMap[$priority];
        }
        if (!in_array($priority, ['high', 'middle', 'low'], true)) {
            $priority = 'middle';
        }

        $period = $validated['period'] ?? null;
        $allowedPeriods = ['weekly', 'monthly', 'yearly'];
        if (!in_array($period, $allowedPeriods, true)) {
            $period = null;
        }

        $task = Grouptask::create([
            'user_id' => $validated['created_by'] ?? null,
            'group_id' => $validated['group_id'],
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

    // Update group task.
    public function update(Request $request, $id)
    {
        $task = Grouptask::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Group task not found',
            ], 404);
        }

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

        // Allow partial updates: only validate fields when present
        $rules = [
            'group_id' => ['sometimes', 'integer'],
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'week_days' => ['sometimes'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'required_minutes' => ['nullable', 'integer', 'min:1'],
            'priority' => ['sometimes', 'nullable', 'string', 'max:20'],
            'color' => ['sometimes', 'string', 'max:20'],
            'period' => ['sometimes', 'nullable', 'string', 'max:20'],
            'notification_enabled' => ['sometimes', 'boolean'],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', 'nullable', 'string', 'max:20'],
            'created_by' => ['sometimes', 'integer'],
        ];

        $validated = $request->validate($rules);

        $updateData = [];

        if ($request->has('created_by')) {
            $updateData['created_by'] = $validated['created_by'];
            $updateData['user_id'] = $validated['created_by'] ?? null;
        }

        if ($request->has('group_id')) {
            $updateData['group_id'] = $validated['group_id'];
        }

        if ($request->has('title')) {
            $updateData['title'] = $validated['title'];
        }

        if ($request->has('content')) {
            $updateData['content'] = $validated['content'] ?? null;
        }

        if ($request->has('week_days')) {
            $weekDays = $validated['week_days'];
            $updateData['week_days'] = is_array($weekDays) ? implode(',', $weekDays) : $weekDays;
        }

        if ($request->has('start_time')) {
            $updateData['start_time'] = $validated['start_time'] ?? null;
        }

        if ($request->has('required_minutes')) {
            $updateData['required_minutes'] = $validated['required_minutes'] ?? null;
        }

        if ($request->has('priority')) {
            $priority = $validated['priority'] ?? null;
            $priorityMap = [
                '高' => 'high',
                '中' => 'middle',
                '低' => 'low',
            ];
            if (isset($priorityMap[$priority])) {
                $priority = $priorityMap[$priority];
            }
            if ($priority !== null && !in_array($priority, ['high', 'middle', 'low'], true)) {
                $priority = null;
            }
            $updateData['priority'] = $priority;
        }

        if ($request->has('color')) {
            $updateData['color'] = $validated['color'];
        }

        if ($request->has('period')) {
            $period = $validated['period'] ?? null;
            $allowedPeriods = ['weekly', 'monthly', 'yearly'];
            if (!in_array($period, $allowedPeriods, true)) {
                $period = null;
            }
            $updateData['period'] = $period;
        }

        if ($request->has('notification_enabled')) {
            $updateData['notification_enabled'] = $validated['notification_enabled'] ?? 1;
        }

        if ($request->has('start_date')) {
            $updateData['start_date'] = $validated['start_date'] ?? null;
        }

        if ($request->has('end_date')) {
            $updateData['end_date'] = $validated['end_date'] ?? null;
        }

        if ($request->has('status')) {
            $updateData['status'] = $validated['status'] ?? 'active';
        }

        if (!empty($updateData)) {
            $task->update($updateData);
        }

        return response()->json([
            'status' => 'success',
            'task' => $task,
        ]);
    }

    // Delete group task.
    public function destroy($id)
    {
        $task = Grouptask::findOrFail($id);
        $task->delete();

        return response()->json(null, 204);
    }
}
