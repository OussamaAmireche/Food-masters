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

//public routes
Route::post('/client/register', [AuthClientController::class, 'register']);
Route::post('/gestionnaire/register', [AuthGestionnaireController::class, 'register']);
Route::post('/client/login', [AuthClientController::class, 'login']);
Route::post('/gestionnaire/login', [AuthGestionnaireController::class, 'login']);

//protected routes
Route::middleware(['auth:sanctum', 'type.client'])->group(function () {
    // authentification
    Route::post('/client/logout', [AuthClientController::class, 'logout']);
    Route::put('/client/update/{email}', [AuthClientController::class, 'update']);
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
    Route::get('/plat/restaurant/{id}', [PlatController::class, 'index2']);
    Route::get('/plat/search/{name}', [PlatController::class, 'search']);
    Route::post('/plat/store', [PlatController::class, 'store']);
    Route::put('/plat/update/{id}', [PlatController::class, 'update']);
    Route::delete('/plat/destroy/{id}', [PlatController::class, 'destroy']);
    Route::get('/plat/show/{id}', [PlatController::class, 'show']);

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
    Route::get('/commande/{id}', [CommandeController::class, 'index']);
    Route::get('/commande/client/{email}', [CommandeController::class, 'show']);
    Route::get('/commande/client/en_attente/{email}', [CommandeController::class, 'en_attente']);
    Route::post('/commande/store', [CommandeController::class, 'store']);
    Route::post('/commande/accept/{id}', [CommandeController::class, 'accept']);
    Route::post('/commande/refuser/{id}', [CommandeController::class, 'refuser']);
    Route::delete('/commande/destroy/{id}', [CommandeController::class, 'destroy']);
});