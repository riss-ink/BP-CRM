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

Route::get( '/', 'Web\AppController@getApp' )
    ->middleware('auth');
Route::get('/login', 'Web\AppController@getLogin' )
    ->name('login')
    ->middleware('guest');
Route::get('login/google', 'Web\AuthenticationController@getSocialRedirect')->middleware('guest');
Route::get( '/callback/google', 'Web\AuthenticationController@getSocialCallback' )
    ->middleware('guest');
Route::get('logout', 'Web\AuthenticationController@logout');
Route::get('test', 'API\ProductController@index');