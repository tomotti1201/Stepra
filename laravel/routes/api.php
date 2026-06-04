
<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/user/tasks', [TaskController::class, 'userTask']);

Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

