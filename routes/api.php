<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\registerUsersController;
use App\Http\Controllers\getTokenController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\addressBooksController;
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

Route::middleware('auth:sanctum')->get('/getDriverPendingOrders', [ordersController::class, 'getDriverPendingOrders']);
Route::middleware('auth:sanctum')->get('/getDriverOrders', [ordersController::class, 'getDriverOrders']);
Route::middleware('auth:sanctum')->get('/acceptOrder/{id}', [ordersController::class, 'acceptOrder']);
Route::middleware('auth:sanctum')->get('/rejectOrder/{id}', [ordersController::class, 'rejectOrder']);
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
Route::middleware('auth:sanctum')->post('/addToCart', [cartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->get('/getMyCart', [cartController::class, 'getMyCart']);
Route::middleware('auth:sanctum')->post('/updateCart', [cartController::class, 'updateCart']);
//////////////////////////////////////////auth////////////////////////////////
Route::post('/getUserToken', [getTokenController::class, 'getUserToken']);
Route::post('/registerUser', [registerUsersController::class, 'registerUser']);
//////////////////////////////////////addressbook//////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyAddresses', [addressBooksController::class, 'getMyAddresses']);
///////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
