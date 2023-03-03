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

Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/user', 'API\UserController@getUser');
    Route::post('/clients', 'API\ClientController@store');
    Route::get('/clients', 'API\ClientController@index');
    Route::get('clients/create', 'API\ClientController@create');
    Route::get('field-groups', 'API\FieldGroupController@index');
    Route::post('field-groups', 'API\FieldGroupController@store');

    Route::get('product-categories', 'API\ProductCategoryController@index');
    Route::get('/clients/{client}', 'API\ClientController@view');
    Route::get('/clients/{client}/edit', 'API\ClientController@edit');
    Route::patch('/clients/{client}', 'API\ClientController@update');
    Route::get('/clients/{client}/products', 'API\ClientProductController@index');
    Route::put('/clients/{client}/products', 'API\ClientProductController@attachProduct');
    Route::get('/client-products/{clientProduct}', 'API\ClientProductController@edit');
    Route::patch('client-products/{clientProduct}', 'API\ClientProductController@updateCustomFields');
    Route::get('definitions/{definition}/edit', 'API\DefinitionController@edit');
    Route::get('definitions', 'API\DefinitionController@index');
    Route::post('definitions', 'API\DefinitionController@store');

    Route::patch('definitions/{definition}', 'API\DefinitionController@update');
    Route::get('users', 'API\UserController@index');
    Route::get('users/{user}', 'API\UserController@edit');
    Route::patch('users/{user}', 'API\UserController@update');
    Route::patch('users/{user}/markNotifs', 'API\UserController@markNotifs');

    Route::get('roles', 'API\RoleController@index');
    Route::post('roles', 'API\RoleController@store');
    Route::patch('roles/{role}', 'API\RoleController@update');
    Route::delete('roles/{role}', 'API\RoleController@delete');

    Route::get('permissions', 'API\PermissionController@index');
    Route::post('permissions', 'API\PermissionController@store');
    Route::patch('permissions/{permission}', 'API\PermissionController@update');
    Route::delete('permissions/{permission}', 'API\PermissionController@delete');
    Route::get('search', 'API\SearchController@search');
    Route::get('products', 'API\ProductController@index');
    Route::post('products', 'API\ProductController@store');
    Route::get('products/{product}', 'API\ProductController@edit');
    Route::patch('products/{product}', 'API\ProductController@update');

    Route::get('teams', 'API\TeamController@index');
    Route::post('teams', 'API\TeamController@store');
    Route::get('teams/{team}', 'API\TeamController@edit');
    Route::patch('teams/{team}', 'API\TeamController@update');


});
