<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\registerUsersController;
use App\Http\Controllers\getTokenController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\ordersController;
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
///////////////////////////////////resources///////////////////////////////////////////////////////
/*Route::post('/mobGoogleLogin', [socialiteController::class, 'mobGoogleLogin']);
Route::post('/mobFacebookLogin', [socialiteController::class, 'mobFacebookLogin']);*/
Route::middleware('auth:sanctum')->resource('/orders', ordersController::class);
///////////////////////////////////Cart/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyCart', [cartController::class, 'getMyCart']);
Route::middleware('auth:sanctum')->post('/addToCart', [cartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->delete('/deleteCartItem/{id}', [cartController::class, 'deleteCartItem']);
///////////////////////////////////orders/////////////////////////////////////////////////////////
Route::get('/getMyOrders', [ordersController::class, 'getMyOrders']);
Route::middleware('auth:sanctum')->get('/getMyOrdersStatus/{status}', [ordersController::class, 'getMyOrdersStatus']);
Route::post('/repurchaseOrder', [ordersController::class, 'repurchaseOrder']);
///////////////////////////////////resources///////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->resource('/categories', categoriesController::class);
Route::resource('/products', productsController::class);
Route::middleware('auth:sanctum')->resource('/addressBooks', addressBookController::class);
////////////////////////////////////Products////////////////////////////////////////////////////////
Route::get('/productsByCategory/{id}', [productsController::class, 'productsByCategory']);
////////////////////////////////////Categories//////////////////////////////////////////////////////
Route::get('/getMainCategories', [categoriesController::class, 'getMainCategories']);
Route::get('/getSubCategories', [categoriesController::class, 'getSubCategories']);
Route::get('/showSubCategories/{id}', [categoriesController::class, 'showSubCategories']);
Route::get('/getImediateSubCat/{id}', [categoriesController::class, 'getImediateSubCat']);
///////////////////////////////////cart/////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/getMobCart', [cartController::class, 'getMobCart']);
Route::middleware('auth:sanctum')->put('/updateMobCart/{id}', [cartController::class, 'updateMobCart']);
//////////////////////////////////////////auth////////////////////////////////
Route::post('/registerUser', [registerUsersController::class, 'registerUser']);
///////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
