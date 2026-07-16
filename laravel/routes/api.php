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
use App\Http\Controllers\GroupmemberController;

// 認証関連

Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/current-user', [LoginController::class, 'currentUser']);
Route::post('/passwordReset', [PasswordResetController::class, 'passwordReset']);


// ホーム・継続確認

Route::get('/home/tasks', [HomeController::class, 'todayTasks']);

Route::get('/continuity', [ContinuityController::class, 'index']);


// 個人タスク

Route::prefix('tasks')->group(function () {

    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
    Route::post('/{id}/status', [TaskController::class, 'updateStatus']);

});


// スケジュール

Route::prefix('schedules')->group(function () {

    // カレンダー取得
    Route::get('/daily', [ScheduleController::class, 'getDailySchedules']);
    Route::get('/monthly', [ScheduleController::class, 'getMonthlySchedules']);

    // CRUD
    Route::get('/', [ScheduleController::class, 'index']);
    Route::post('/', [ScheduleController::class, 'store']);
    Route::get('/{id}', [ScheduleController::class, 'show']);
    Route::delete('/{id}', [ScheduleController::class, 'destroy']);

});


// グループ

Route::prefix('groups')->group(function () {

    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::post('/join', [GroupController::class, 'join']);
    Route::put('/{id}', [GroupController::class, 'update']);
    Route::delete('/{id}', [GroupController::class, 'destroy']);

});


// グループタスク

Route::prefix('grouptasks')->group(function () {

    Route::get('/', [GroupTaskController::class, 'index']);
    Route::post('/', [GroupTaskController::class, 'store']);
    Route::get('/{id}', [GroupTaskController::class, 'show']);
    Route::put('/{id}', [GroupTaskController::class, 'update']);
    Route::delete('/{id}', [GroupTaskController::class, 'destroy']);

});


// グループメンバー

Route::prefix('groupmembers')->group(function () {

    Route::get('/', [GroupmemberController::class, 'index']);

    Route::post(
        '/join-by-invite',
        [GroupmemberController::class, 'joinByInviteCode']
    );

    Route::get('/{id}', [GroupmemberController::class, 'show']);
    Route::delete('/{id}', [GroupmemberController::class, 'destroy']);

});


// ユーザー設定

Route::get('/user/{id}', [SettingController::class, 'user']);

Route::post(
    '/user/{id}/name',
    [SettingController::class, 'updateName']
);

Route::post(
    '/user/{id}/email',
    [SettingController::class, 'updateMail']
);

Route::post(
    '/user/{id}/password/check',
    [SettingController::class, 'checkPassword']
);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
Route::get('/schedules/monthly', [ScheduleController::class, 'getMonthlySchedules']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
Route::get('/groups', [GroupController::class, 'index']);
Route::post('/groups', [GroupController::class, 'store']);
Route::post('/groups/join', [GroupController::class, 'join']);
Route::put('/groups/{id}', [GroupController::class, 'update']);
Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
Route::get('/grouptasks', [GroupTaskController::class, 'index']);
Route::post('/grouptasks', [GroupTaskController::class, 'store']);
Route::delete('/grouptasks/{id}', [GroupTaskController::class, 'destroy']);
Route::get('/groupmembers', [GroupmemberController::class, 'index']);
Route::post('/groupmembers/join-by-invite', [GroupmemberController::class, 'joinByInviteCode']);
Route::get('/groupmembers/{id}', [GroupmemberController::class, 'show']);
Route::delete('/groupmembers/{id}', [GroupmemberController::class, 'destroy']);

?>
