<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);
        $userId = $request->query('user_id', Auth::id());

        $query = Schedule::query()
            ->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month)
            ->orderBy('scheduled_date');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $schedule = Schedule::create([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id ?? Auth::id(),
            'scheduled_date' => $request->scheduled_date,
        ]);

        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        $schedule = Schedule::findOrFail($id);

        return response()->json($schedule);
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'message' => 'deleted',
        ]);
    }
}
