<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function todayTasks(Request $request)
    {
        $userId = $request->query('user_id');
        $date = $request->query('date');
        $targetDate = $date
            ? Carbon::parse($date)->format('Y-m-d')
            : Carbon::today()->format('Y-m-d');

        $tasks = Schedule::where('user_id', $userId)
            ->where('scheduled_date', $targetDate)
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->title,
                    'start_time' => $schedule->start_time,
                    'required_minutes' => $schedule->required_minutes,
                    'priority' => $schedule->priority,
                    'color' => $schedule->color,
                    'status' => $schedule->status,
                    'content' => $schedule->content,
                ];
            });

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }
}
