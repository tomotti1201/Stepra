
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupTaskController;





Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/passwordReset', [PasswordResetController::class, 'passwordReset']);



Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
Route::get('/groups', [GroupController::class, 'index']);
Route::post('/groups', [GroupController::class, 'store']);
Route::post('/groups/join', [GroupController::class, 'join']);
Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
Route::get('/grouptasks', [GroupTaskController::class, 'index']);
Route::post('/grouptasks', [GroupTaskController::class, 'store']);
Route::delete('/grouptasks/{id}', [GroupTaskController::class, 'destroy']);
?>
