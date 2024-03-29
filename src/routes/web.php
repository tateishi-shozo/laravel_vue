<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', function () {
    return view('welcome');
});

Route::get('/index', function () {
    return view('index');
});

Route::get('/comment', function () {
    return view('comment');
});

Route::get('/login',function(){
    return view('auth.login');
});

Route::get('/register',function(){
    return view('auth.register');
});