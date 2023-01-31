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
    Route::put('/books/{update}','BookController@update');
    Route::delete('/books/{id}','BookController@destroy');

    // //コメントの投稿系
    // Route::post('/books/comments','CommentController@create');

    //ログアウト
    Route::get('/logout','AuthController@logout');
});

//コメントの投稿系
Route::post('/books/comments','CommentController@create');

//本・コメント閲覧
Route::get('/books','BookController@index');
    Route::delete('/books/{id}','BookController@destroy');
    Route::get('/books/comments/{id}','CommentController@show');

//ログイン・ユーザー登録
Route::post('/register','AuthController@register');
Route::post('/login','AuthController@login');