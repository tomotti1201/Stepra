<?php

namespace App\Http\Controllers;

use App\Models\GroupSchedule;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupScheduleController extends Controller
{
    /**
     * グループカレンダー（月間取得）
     */
    public function getMonthlySchedules(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);
        $groupId = $request->query('group_id');

        $schedules = GroupSchedule::query()
            ->where('group_id', $groupId)
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month)
            ->orderBy('scheduled_date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'group_task_id' => $schedule->group_task_id,
                    'group_id' => $schedule->group_id,
                    'user_id' => $schedule->user_id,
                    'scheduled_date' => $schedule->scheduled_date,

                    'title' => $schedule->title,
                    'content' => $schedule->content,
                    'start_time' => $schedule->start_time,
                    'required_minutes' => $schedule->required_minutes,
                    'priority' => $schedule->priority,
                    'color' => $schedule->color ?? '#198754',
                    'status' => $schedule->status,
                ];
            });

        return response()->json([
            'status' => 'success',
            'schedules' => $schedules,
        ]);
    }
    public function detail(Request $request, $id)
{
    $group = Group::findOrFail($id);

    return view('groupScheduleDetail', [
        'group'   => $group,
        'groupId' => $id,
        'date'    => $request->date,
    ]);
}
}