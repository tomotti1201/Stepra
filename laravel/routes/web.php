<?php

use App\Http\Controllers\TaskController;
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
    return response()->file(resource_path('views/frontend/schedule.html'));
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

Route::get('/gurupu', function () {
    return response()->file(resource_path('views/frontend/gurupu.html'));
});

Route::get('/gtasutkuitiran', function () {
    return response()->file(resource_path('views/frontend/gtasutkuitiran.html'));
});

Route::get('/gtaskukuitiran', function () {
    return response()->file(resource_path('views/frontend/gtasutkuitiran.html'));
});

Route::get('/gurupjouhouhensyu', function () {
    return response()->file(resource_path('views/frontend/gurupjouhouhensyu.html'));
});

Route::get('/gurupujouhouhensyu', function () {
    return response()->file(resource_path('views/frontend/gurupjouhouhensyu.html'));
});

Route::get('/gurupumokuhyosinki', function () {
    return response()->file(resource_path('views/frontend/gurupumokuhyosinki.html'));
});

Route::get('/gurupusyu', function () {
    return response()->file(resource_path('views/frontend/gurupusyu.html'));
});

Route::get('/gurutaskukuhen', function () {
    return response()->file(resource_path('views/frontend/gurutasukuhen.html'));
});

Route::get('/sinnkiguru', function () {
    return response()->file(resource_path('views/frontend/sinnkiguru.html'));
});

Route::get('/kiroku', function () {
    return response()->file(dirname(base_path()) . '/tokuhiduke.html');
});

Route::get('/rireki', function () {
    return response()->file(dirname(base_path()) . '/keizoku.html');
});

Route::get('/gekkankarenda', function () {
    return response()->file(dirname(base_path()) . '/gekkankarenda.html');
});

Route::get('/mokuhyouitiran', function () {
    return response()->file(dirname(base_path()) . '/mokuhyouitiran.html');
});

Route::get('/im/{filename}', function (string $filename) {
    $imagePath = dirname(base_path()) . '/im/' . $filename;

    abort_unless(is_file($imagePath), 404);

    return response()->file($imagePath);
})->where('filename', '[A-Za-z0-9_.-]+');
