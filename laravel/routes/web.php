<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup2',function(){
    return view('signup2');
});

Route::get('/login',function(){
    return view('login');
});

Route::get('/newuser',function(){
    return view('newuser');
});

Route::get('/home',function(){
    return view('home');
});

Route::get('/passwordReset',function(){
    return view('passwordReset');
});