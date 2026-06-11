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

Route::get('/calendar',function(){
    return view('calendar');
});

Route::get('/group',function(){
    return view('group');
});

Route::get('/setting',function(){
    return view('setting');
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
