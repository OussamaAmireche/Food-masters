<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\RatingPlatController;
use App\Http\Controllers\RatingRestaurantController;
use App\Http\Controllers\API\Auth\AuthClientController;
use App\Http\Controllers\API\Auth\AuthGestionnaireController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// authentification
Route::post('/client/register', [AuthClientController::class, 'register']);
Route::post('/gestionnaire/register', [AuthGestionnaireController::class, 'register']);
Route::post('/client/login', [AuthClientController::class, 'login']);
Route::post('/gestionnaire/login', [AuthGestionnaireController::class, 'login']);
Route::post('/client/logout', [AuthClientController::class, 'logout']);
Route::post('/gestionnaire/logout', [AuthGestionnaireController::class, 'logout']);

//categories
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{nom}', [CategorieController::class, 'show']);

//restaurant
Route::get('/restaurant', [RestaurantController::class, 'index']);
Route::get('/restaurant/search/{name}', [RestaurantController::class, 'search']);
Route::post('/restaurant/store', [RestaurantController::class, 'store']);
Route::put('/restaurant/update/{id}', [RestaurantController::class, 'update']);
Route::delete('/restaurant/destroy/{id}', [RestaurantController::class, 'destroy']);
Route::get('/restaurant/{id}', [RestaurantController::class, 'show']);

//plat
Route::get('/plat/{name_categorie}', [PlatController::class, 'index']);
Route::get('/plat/search/{name}', [PlatController::class, 'search']);
Route::post('/plat/store', [PlatController::class, 'store']);
Route::put('/plat/update/{id}', [PlatController::class, 'update']);
Route::delete('/plat/destroy/{id}', [PlatController::class, 'destroy']);
Route::get('/plat/{id}', [PlatController::class, 'show']);

//favoris
Route::post('/favori/store', [FavoriController::class, 'store']);
Route::delete('/favori/destroy/{id}', [FavoriController::class, 'destroy']);
Route::get('/favori/{email}', [FavoriController::class, 'show']);

//rating plat
Route::post('/rating_plat/store', [RatingPlatController::class, 'store']);
Route::put('/rating_plat/update/{id}', [RatingPlatController::class, 'update']);
Route::delete('/rating_plat/destroy/{id}', [RatingPlatController::class, 'destroy']);
Route::get('/rating_plat/{id}', [RatingPlatController::class, 'show']);

//rating restaurant
Route::post('/rating_restaurant/store', [RatingRestaurantController::class, 'store']);
Route::put('/rating_restaurant/update/{id}', [RatingRestaurantController::class, 'update']);
Route::delete('/rating_restaurant/destroy/{id}', [RatingRestaurantController::class, 'destroy']);
Route::get('/rating_restaurant/{id}', [RatingRestaurantController::class, 'show']);

//commande
Route::post('/commande/store', [CommandeController::class, 'store']);


Route::middleware(['auth:sanctum', 'type.client'])->group(function () {
    
});