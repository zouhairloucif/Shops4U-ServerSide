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

/*-------  Api Auth  -------*/

Route::group([
	'prefix' => 'auth'
], function ($router) {
	Route::post('login', 'AuthController@login');
	Route::post('logout', 'AuthController@logout');
	Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');
	Route::post('userOrFail', 'AuthController@userOrFail');
	Route::post('isValid', 'AuthController@isValid');

});

/*-------  Api user  -------*/

Route::group([
	'prefix' => 'user'
], function ($router) {
	Route::get('show', 'BackOffice\UserController@showUser');
	Route::post('signup', 'BackOffice\UserController@signup');
	Route::post('update', 'BackOffice\UserController@UpdateUser');
});

/*-------  Api Produits  -------*/

Route::group([
	'prefix' => 'produit'
], function ($router) {
	Route::get('all', 'BackOffice\CatalogueController@AllProduits');
	Route::post('store', 'BackOffice\CatalogueController@StoreProduit');
	Route::delete('delete/{id}', 'BackOffice\CatalogueController@DeleteProduit');
});

/*-------  Api Catalogue  -------*/

Route::group([
	'prefix' => 'categorie'
], function ($router) {
	Route::get('all', 'BackOffice\CatalogueController@AllCategory');
	Route::get('show/{id}', 'BackOffice\CatalogueController@showCategory');
	Route::post('store', 'BackOffice\CatalogueController@StoreCategory');
	Route::put('update/{id}', 'BackOffice\CatalogueController@UpdateCategory');
	Route::delete('delete/{id}', 'BackOffice\CatalogueController@DeleteCategory');
});

/*-------  Api Catalogue  -------*/

Route::group([
	'prefix' => 'reduction'
], function ($router) {
	Route::get('all', 'BackOffice\CatalogueController@AllReduction');
	Route::get('show/{id}', 'BackOffice\CatalogueController@showReduction');
	Route::post('store', 'BackOffice\CatalogueController@StoreReduction');
	Route::put('update/{id}', 'BackOffice\CatalogueController@UpdateReduction');
	Route::delete('delete/{id}', 'BackOffice\CatalogueController@DeleteReduction');
});

/*-------  Api Catalogue  -------*/

Route::group([
	'prefix' => 'fournisseur'
], function ($router) {
	Route::get('all', 'BackOffice\CatalogueController@AllFournisseur');
	Route::get('show/{id}', 'BackOffice\CatalogueController@showFournisseur');
	Route::post('store', 'BackOffice\CatalogueController@StoreFournisseur');
	Route::put('update/{id}', 'BackOffice\CatalogueController@UpdateFournisseur');
	Route::delete('delete/{id}', 'BackOffice\CatalogueController@DeleteFournisseur');
});

/*-------  Api Marque  -------*/

Route::group([
	'prefix' => 'marque'
], function ($router) {
	Route::get('all', 'BackOffice\CatalogueController@AllMarque');
	Route::get('show/{id}', 'BackOffice\CatalogueController@showMarque');
	Route::post('store', 'BackOffice\CatalogueController@StoreMarque');
	Route::post('update/{id}', 'BackOffice\CatalogueController@UpdateMarque');
	Route::delete('delete/{id}', 'BackOffice\CatalogueController@DeleteMarque');
});

/*-------  Api Boutique  -------*/

Route::group([
	'prefix' => 'boutique'
], function ($router) {
	Route::post('store', 'BackOffice\BoutiqueController@StoreBoutique');
	Route::get('show', 'BackOffice\BoutiqueController@showBoutique');
	Route::post('update', 'BackOffice\BoutiqueController@UpdateBoutique');
	Route::get('maintenance/show', 'BackOffice\BoutiqueController@showMaintenance');
	Route::post('maintenance', 'BackOffice\BoutiqueController@maintenance');
});

/*-------  Api Paiement  -------*/

Route::group([
	'prefix' => 'paiement'
], function ($router) {
	Route::get('mode-paiements', 'BackOffice\PaiementController@modePaiements');
});

/*-------  Api Livraison  -------*/

Route::group([
	'prefix' => 'livraison'
], function ($router) {
	Route::post('zone/store', 'BackOffice\LivraisonController@storeZone');
	Route::get('zone/all', 'BackOffice\LivraisonController@allZone');
	Route::delete('zone/delete/{id}', 'BackOffice\LivraisonController@DeleteZone');
	Route::post('pays/store', 'BackOffice\LivraisonController@storePays');
	Route::get('pays/all', 'BackOffice\LivraisonController@allPays');
	Route::delete('pays/delete/{id}', 'BackOffice\LivraisonController@DeletePays');
	Route::post('ville/store', 'BackOffice\LivraisonController@storeVille');
	Route::get('ville/all', 'BackOffice\LivraisonController@allVille');
	Route::delete('ville/delete/{id}', 'BackOffice\LivraisonController@DeleteVille');
	Route::post('transporteur/store', 'BackOffice\LivraisonController@storeTransporteur');
	Route::get('transporteur/all', 'BackOffice\LivraisonController@allTransporteur');
	Route::delete('transporteur/delete/{id}', 'BackOffice\LivraisonController@DeleteTransporteur');
});

/*-------  Api Client  -------*/

Route::group([
	'prefix' => 'client'
], function ($router) {
	Route::get('all', 'BackOffice\ClientsController@all');
	Route::post('store', 'BackOffice\ClientsController@store');
	Route::get('show/{id}', 'BackOffice\ClientsController@show');
	Route::put('update/{id}', 'BackOffice\ClientsController@update');
	Route::delete('delete/{id}', 'BackOffice\ClientsController@delete');
});

/*******************  Super admin *******************/

Route::group([
	'prefix' => 'sa/user'
], function ($router) {
	// Route::get('all', 'BackOffice\SaUserController@allUser');
	Route::post('add/superadmin', 'BackOffice\SaUserController@addSuperAdmin');
	Route::post('add/vendeur', 'BackOffice\SaUserController@addVendeur');
	Route::post('add/client', 'BackOffice\SaUserController@addClient');
	Route::get('showSA', 'BackOffice\SaUserController@showSuperAdmin');
	Route::get('showC', 'BackOffice\SaUserController@showClient');
	Route::get('showV', 'BackOffice\SaUserController@showVendeur');
	Route::get('allSa', 'BackOffice\SaUserController@allSa');
	Route::get('allV', 'BackOffice\SaUserController@allV');
	Route::get('allC', 'BackOffice\SaUserController@allC');
	Route::delete('delete/{id}', 'BackOffice\SaUserController@deleteUser');
});