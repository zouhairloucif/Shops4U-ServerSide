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

/*===========================  Api Auth  ===========================*/

Route::group([
	'prefix' => 'auth'
], function ($router) {
	Route::post('login', 'AuthController@login');
	Route::post('logout', 'AuthController@logout');
	Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');
	Route::post('userOrFail', 'AuthController@userOrFail');

});

/*===========================  Api user  ===========================*/

Route::group([
	'prefix' => 'user'
], function ($router) {
	Route::post('signup', 'BackOffice\UserController@signup');
});

/*===========================  Api Catalogue  ===========================*/

Route::group([
	'prefix' => 'category'
], function ($router) {
	Route::get('all', 'BackOffice\CatalogueController@AllCategory');
	Route::get('show/{id}', 'BackOffice\CatalogueController@showCategory');
	Route::post('store', 'BackOffice\CatalogueController@StoreCategory');
	Route::put('update/{id}', 'BackOffice\CatalogueController@UpdateCategory');
	Route::delete('destroy/{id}', 'BackOffice\CatalogueController@DestroyCategory');
});