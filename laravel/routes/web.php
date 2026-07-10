<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use App\Models\Group;
use App\Models\Groupmember;
use App\Models\Grouptask;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',function(){
    return view('login');
});

Route::get('/login',function(){
    return view('login');
});

Route::get('/newuser',function(){
    return view('newuser');
});

Route::get('/taskCreate',function(){
    return view('taskCreate');
});

Route::get('/schedule', function () {
    return view('schedules');
});

Route::get('/task',function(){
    return view('task');
});

Route::get('/taskedit', function () {
    return view('taskedit');
});

Route::get('/schedules',function(){
    return view('schedules');
});

Route::get('/scheduleDetail', [ScheduleController::class, 'detail']);

Route::get('/group',function(){
    return view('group');
});

Route::get('/groupCreate',function(){
    return view('groupCreate');
});

Route::get('/continuity',function(){
    return view('continuity');
});

Route::get('/setting',function(){
    return view('setting');
});

Route::get('/logout',function(){
    return view('logout');
});



Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/user/tasks', [TaskController::class, 'userTask']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/home',function(){
    return view('home');
});

Route::get('/passwordReset',function(){
    return view('passwordReset');
});

Route::get('/tasks/{id}', [TaskController::class, 'show']);

Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

Route::prefix('setting')->group(function () {

    Route::view('/user', 'setting.settingUser');

    Route::view('/notification', 'setting.settingNotification');

    Route::view('/design', 'setting.settingDesign');

    Route::view('/logout', 'setting.settingLogout');

});
Route::get('/group',function(){
    $groups = Group::all();
    return view('group', ['groups' => $groups]);
});

Route::get('/gtasutkuitiran/{id}', function ($id) {
    $tasklist = Grouptask::where('group_id', $id)->get();
    $group = Group::findOrFail($id);
    return view('gtasutkuitiran',[
        'group' => $group,
        'tasklist' => $tasklist
    ]);
});

Route::get('/gurupjouhouhensyu/{id}', function ($id) {
    $group = Group::findOrFail($id);
    $member = Groupmember::where('group_id',$id)->get();
    return view('gurupjouhouhensyu',[
        'group' => $group,
        'member'=>$member
    ]);
});


Route::get('/gurupumokuhyosinki/{id}', function ($id) {
    $group = Group::findOrFail($id);
    return view('gurupumokuhyosinki',[
        'group' => $group
    ]);
});

Route::get('/gurupusyu/{id}', function ($id) {
    $group = Group::findOrFail($id);
    $tasklist = Grouptask::where('group_id', $id)->get();

    return view('gurupusyu',[
        'group' => $group,
        'tasklist' => $tasklist
    ]);

});

Route::get('/gurutaskukuhen/{id}', function ($id) {
    $group = Group::findOrFail($id);
    return view('gurutasukuhen',[
        'group' => $group 
    ]);
});

Route::get('/sinnkiguru', function () {
    return view('sinnkiguru');
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

Route::get('/im/{filename}', function (string $filename) {
    $imagePath = dirname(base_path()) . '/im/' . $filename;

    abort_unless(is_file($imagePath), 404);

    return response()->file($imagePath);
})->where('filename', '[A-Za-z0-9_.-]+');
