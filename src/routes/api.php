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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/books','BookController');

Route::middleware('auth:sanctum')->get('/books', 'BookController@index');

Route::delete('/destroy/{id}', 'BookController@destroy');

Route::put('/update/{update}', 'BookController@update');

Route::post('/register','AuthController@register');

Route::post('/login','AuthController@login');