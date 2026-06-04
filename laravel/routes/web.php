<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',function(){
    return view('login');
});

Route::get('/roguin',function(){
    return view('roguin');
});

Route::get('/newuser',function(){
    return view('newuser');
});

Route::get('/schedule', function () {
    return response()->file(resource_path('views/frontend/schedule.html'));
});

Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/user/tasks', [TaskController::class, 'userTask']);
Route::post('/tasks', [TaskController::class, 'store']);
