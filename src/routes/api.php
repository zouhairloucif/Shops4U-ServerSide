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
	Route::post('signup', 'UserController@signup');
});

/*===========================  Api Catalogue  ===========================*/

Route::post('/categorie/store', 'CatalogueController@StoreCategories');

Route::get('/categorie/all', 'CatalogueController@AllCategories');