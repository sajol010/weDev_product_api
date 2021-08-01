<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([

    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});


Route::group([

   'middleware' => 'auth:api',
    'namespace' => 'App\Http\Controllers',

], function () {

    Route::get('product/list', 'ProductController@list');
    Route::post('product/add', 'ProductController@create');
    Route::get('product/{id}', 'ProductController@show');
    Route::put('product/{id}', 'ProductController@edit');
    Route::delete('product/{id}', 'ProductController@destroy');


});
