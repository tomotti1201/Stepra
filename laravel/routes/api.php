<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ContinuityController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupTaskController;
use App\Http\Controllers\SettingController;


Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/passwordReset', [PasswordResetController::class, 'passwordReset']);

Route::get('/home/tasks', [HomeController::class, 'todayTasks']);

Route::get('/continuity', [ContinuityController::class, 'index']);

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);

    Route::get('/{id}', [TaskController::class, 'show']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);

    Route::post('/{id}/status', [TaskController::class, 'updateStatus']);
});

Route::prefix('schedules')->group(function () {

    // 日別・月別（カレンダー用）
    Route::get('/daily', [ScheduleController::class, 'getDailySchedules']);
    Route::get('/monthly', [ScheduleController::class, 'getMonthlySchedules']);

    // CRUD
    Route::get('/', [ScheduleController::class, 'index']);
    Route::post('/', [ScheduleController::class, 'store']);

    Route::get('/{id}', [ScheduleController::class, 'show']);
    Route::delete('/{id}', [ScheduleController::class, 'destroy']);
});

Route::prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::post('/join', [GroupController::class, 'join']);
    Route::delete('/{id}', [GroupController::class, 'destroy']);
});

Route::prefix('grouptasks')->group(function () {
    Route::get('/', [GroupTaskController::class, 'index']);
    Route::post('/', [GroupTaskController::class, 'store']);
    Route::delete('/{id}', [GroupTaskController::class, 'destroy']);
});

Route::get('/user/{id}', [SettingController::class, 'user']);
Route::post(
    '/user/{id}/name',
    [SettingController::class,'updateName']
);

Route::post(
    '/user/{id}/email',
    [SettingController::class,'updateMail']
);
Route::post(
    '/user/{id}/password/check',
    [SettingController::class, 'checkPassword']
);