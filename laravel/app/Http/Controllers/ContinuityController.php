<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class ContinuityController extends Controller
{
    public function index(Request $request)
{
    $userId = $request->query('user_id');
    $user = User::findOrFail($userId);
    $startDate = Task::where('user_id', $userId)
        ->min('start_date');

    if (!$startDate) {
        return response()->json([
            'status' => 'success',
            'name' => $user->name,
            'icon' => $user->icon,
            'rate' => 0,
            'total' => 0,
            'completed' => 0,
            'streak' => 0,
            'month_total' => 0,
            'month_completed' => 0,
            'medal' => 'bronze-medal.png'
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

$schedules = Schedule::where('user_id', $userId)
    ->orderBy('scheduled_date')
    ->get();
$streak = 0;

$date = Carbon::today();

while (true) {

    $schedule = Schedule::where('user_id', $userId)
        ->whereDate('scheduled_date', $date)
        ->first();

    if (!$schedule) {
        break;
    }

    if ($schedule->status != 'completed') {
        break;
    }

    $streak++;

    $date->subDay();
}
$monthSchedules = Schedule::where('user_id', $userId)
    ->whereYear('scheduled_date', Carbon::now()->year)
    ->whereMonth('scheduled_date', Carbon::now()->month)
    ->get();

$monthTotal = $monthSchedules->count();

$monthCompleted = $monthSchedules
    ->where('status', 'completed')
    ->count();
if ($rate >= 90) {

    $medal = "platinum-medal.png";

} elseif ($rate >= 80) {

    $medal = "gold-medal.png";

} elseif ($rate >= 70) {

    $medal = "silver-medal.png";

} elseif ($rate >= 60) {

    $medal = "bronze-medal.png";

} else {

    $medal = "bronze-medal.png";
}
    return response()->json([
    'status' => 'success',
    'name' => $user->name,
    'icon' => $user->icon,
    'rate' => $rate,
    'total' => $total,
    'completed' => $completed,
    'streak' => $streak,
    'month_total' => $monthTotal,
    'month_completed' => $monthCompleted,
    'medal' => $medal,
    'start_date' => $startDate,
    'today' => $today->toDateString()
]);
}
}
