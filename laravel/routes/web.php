<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
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
