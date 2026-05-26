<?php

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