<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;


Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/passwordReset', [PasswordResetController::class, 'resetPassword']);