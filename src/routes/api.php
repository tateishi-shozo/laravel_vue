<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Monolog\Handler\RotatingFileHandler;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {

    //本の投稿系
    Route::post('/books','BookController@store');
    Route::put('/books/{id}','BookController@update');
    Route::delete('/books/{id}','BookController@destroy');

    // //コメントの投稿系
    // Route::post('/books/comments','CommentController@create');

    //ログアウト
    Route::get('/logout','AuthController@logout');
});

//コメント
Route::get('/book/comment/{id}','CommentController@show');
Route::post('/book/comment','CommentController@create');
Route::delete('/book/comment/{id}','CommentController@destroy');

//本
Route::get('/books','BookController@index');
Route::get('/book/{id}','BookController@show');
Route::delete('/books/{id}','BookController@destroy');

//ログイン・ユーザー登録
Route::post('/register','AuthController@register');
Route::post('/login','AuthController@login');