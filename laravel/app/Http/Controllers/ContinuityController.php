<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Task;
use Carbon\Carbon;

class ContinuityController extends Controller
{
    public function index(Request $request)
{
    $userId = $request->query('user_id');

    $startDate = Task::where('user_id', $userId)
        ->min('start_date');

    if (!$startDate) {
        return response()->json([
            'status' => 'success',
            'rate' => 0,
            'total' => 0,
            'completed' => 0
        ]);
    }

    $start = Carbon::parse($startDate)->startOfDay();
$today = Carbon::today()->startOfDay();

$total = Schedule::where('user_id', $userId)
    ->whereBetween('scheduled_date', [
        $start->format('Y-m-d'),
        $today->format('Y-m-d')
    ])
    ->count();

$completed = Schedule::where('user_id', $userId)
    ->whereBetween('scheduled_date', [
        $start->format('Y-m-d'),
        $today->format('Y-m-d')
    ])
    ->where('status', 'completed')
    ->count();

$rate = $total > 0
    ? round(($completed / $total) * 100, 1)
    : 0;

    return response()->json([
        'status' => 'success',
        'rate' => $rate,
        'total' => $total,
        'completed' => $completed,
        'start_date' => $startDate,
        'today' => $today->toDateString()
    ]);
}
}