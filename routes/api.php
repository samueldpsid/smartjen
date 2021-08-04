<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('signup', 'API\UserController@signup');
Route::post('login', 'API\UserController@login');

Route::middleware('auth:api')->group(function () {
    Route::get('logout', 'API\UserController@logout');
    Route::get('list-user', 'API\UserController@listuser');
	Route::get('list-teacher', 'API\UserController@listteacher');
	Route::get('list-student', 'API\UserController@liststudent');
});
