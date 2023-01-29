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

    Route::apiResource('/books','BookController');

    Route::get('/logout','AuthController@logout');

});

Route::post('/register','AuthController@register');

Route::post('/login','AuthController@login');