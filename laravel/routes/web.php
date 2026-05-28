<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signup2',function(){
    return view('signup2');
});

Route::get('/roguin',function(){
    return view('roguin');
});

Route::get('/newuser',function(){
    return view('newuser');
});