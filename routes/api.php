<?php

use Illuminate\Http\Request;

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

Route::post('auth/login', 'Api\AuthController@login')->middleware('api');

Route::group(['middleware' => ['auth:api'], 'namespace' => 'Api'], function () {
    Route::post('auth/logout', 'AuthController@logout');
    Route::post('auth/refresh', 'AuthController@refresh');
    Route::post('auth/user', 'AuthController@currentUser');

    Route::group(['middleware' => 'acl', 'as' => 'api.'], function () {
        Route::apiResource('/department', 'DepartmentController');
    });
});
