<?php

use App\Http\Controllers\GroupTaskController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GroupScheduleController;
use App\Models\Group;
use App\Models\Groupmember;
use App\Models\Grouptask;
use Illuminate\Support\Facades\Route;

// トップ・認証

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/newuser', function () {
    return view('newuser');
});
Route::get('/logout', function () {
    return view('logout');
});
Route::get('/passwordReset', function () {
    return view('passwordReset');
});
Route::get('/home', function () {
    return view('home');
});

// 個人タスク画面

Route::get('/taskCreate', function () {
    return view('taskCreate');
});
Route::get('/task', function () {
    return view('task');
});
Route::get('/taskedit', function () {
    return view('taskedit');
});
Route::get('/schedule', function () {
    return view('schedules');
});
Route::get('/schedules', function () {
    return view('schedules');
});
Route::get('/scheduleDetail', [ScheduleController::class, 'detail']);

// 個人タスクAPI

Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/user/tasks', [TaskController::class, 'userTask']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

// グループ画面

Route::get('/group/create', function () {
    return view('groupCreate');
});

// グループ一覧
Route::get('/group', function () {

    $userId = session('user_id');

    $groupIds = Groupmember::where('user_id', $userId)
        ->pluck('group_id');

    $groups = Group::whereIn('id', $groupIds)->get();

    return view('group', compact('groups'));
});

// グループタスク一覧
Route::get('/group/{id}/tasks', function ($id) {
    $tasklist = Grouptask::where('group_id', $id)->get();
    $group = Group::findOrFail($id);

    return view('gtasutkuitiran', [
        'group' => $group,
        'tasklist' => $tasklist
    ]);
});

// グループ情報編集
Route::get('/group/{id}/edit', function ($id) {
    $group = Group::findOrFail($id);
    $member = Groupmember::where('group_id', $id)->get();

    return view('gurupjouhouhensyu', [
        'group' => $group,
        'member' => $member
    ]);
});

// グループタスク作成
Route::get('/group/{id}/task/create', function ($id) {
    $group = Group::findOrFail($id);

    return view('gurupumokuhyosinki', [
        'group' => $group
    ]);
});

// グループスケジュール
Route::get('/group/{id}/schedule', function ($id) {
    $group = Group::findOrFail($id);
    $tasklist = Grouptask::where('group_id', $id)->get();

    return view('gurupusyu', [
        'group' => $group,
        'tasklist' => $tasklist
    ]);
});

Route::get(
    '/group/{id}/scheduleDetail',
    [GroupScheduleController::class, 'detail']
);

// グループタスク編集
Route::get('/group/{group_id}/task/edit', function ($group_id) {
    $task_id = request('task_id');

    $task = Grouptask::where('id', $task_id)
        ->where('group_id', $group_id)
        ->firstOrFail();

    return view('gurutasukuhen', [
        'task' => $task
    ]);
});
// グループタスクAPI

Route::get('/api/grouptasks/{id}', [GroupTaskController::class, 'show']);
Route::put('/api/grouptasks/{id}', [GroupTaskController::class, 'update']);
Route::delete('/api/grouptasks/{id}', [GroupTaskController::class, 'destroy']);

// 設定

Route::get('/setting', function () {
    return view('setting');
});

Route::prefix('setting')->group(function () {
    Route::view('/user', 'setting.settingUser');
    Route::view('/notification', 'setting.settingNotification');
    Route::view('/design', 'setting.settingDesign');
    Route::view('/logout', 'setting.settingLogout');
});

// その他画面

Route::get('/continuity', function () {
    return view('continuity');
});
Route::get('/kiroku', function () {
    return view('tokuhiduke');
});
Route::get('/rireki', function () {
    return view('keizoku');
});
Route::get('/gekkankarenda', function () {
    return view('gekkankarenda');
});
Route::get('/mokuhyouitiran', function () {
    return view('mokuhyouitiran');
});

// 画像

Route::get('/im/{filename}', function (string $filename) {
    $imagePath = dirname(base_path()) . '/im/' . $filename;

    abort_unless(is_file($imagePath), 404);

    return response()->file($imagePath);
})->where('filename', '[A-Za-z0-9_.-]+');
