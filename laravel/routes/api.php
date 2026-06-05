<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;



Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/passwordReset', [PasswordResetController::class, 'passwordReset']);

//Route::get('/user/tasks', [TaskController::class, 'userTask']);
Route::post('/tasks', [TaskController::class, 'taskCreate']);
Route::get('/tasks', [TaskController::class, 'index']);
