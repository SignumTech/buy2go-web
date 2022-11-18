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
use App\Http\Controllers\wishlistsController;
use App\Http\Controllers\balancesController;
use App\Http\Controllers\agentsController;
use App\Http\Controllers\creditsController;
use App\Http\Controllers\notificationsController;
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
///////////////////////////////////Notifications/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyNotifications', [notificationsController::class, 'getMyNotifications']);
///////////////////////////////////resources///////////////////////////////////////////////////////
/*Route::post('/mobGoogleLogin', [socialiteController::class, 'mobGoogleLogin']);
Route::post('/mobFacebookLogin', [socialiteController::class, 'mobFacebookLogin']);*/
Route::middleware('auth:sanctum')->resource('/orders', ordersController::class);
///////////////////////////////////Balances/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyBalance', [balancesController::class, 'getMyBalance']);
Route::middleware('auth:sanctum')->get('/balanceHistory', [balancesController::class, 'balanceHistory']);
///////////////////////////////////Cart/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyCart', [cartController::class, 'getMyCart']);
Route::middleware('auth:sanctum')->post('/addToCart', [cartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->delete('/deleteCartItem/{id}', [cartController::class, 'deleteCartItem']);
///////////////////////////////////agents/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/searchShop', [agentsController::class, 'searchShop']);
Route::middleware('auth:sanctum')->put('/withdrawCash/{id}', [agentsController::class, 'withdrawCash']);
Route::middleware('auth:sanctum')->post('/placeRequest', [agentsController::class, 'placeRequest']);
Route::middleware('auth:sanctum')->get('/showPaymentRequest/{id}', [agentsController::class, 'showPaymentRequest']);
Route::middleware('auth:sanctum')->get('/getMyRequests', [agentsController::class, 'getMyRequests']);
///////////////////////////////////orders/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/addAgentOrder', [ordersController::class, 'addAgentOrder']);
Route::middleware('auth:sanctum')->get('/getMyOrders', [ordersController::class, 'getMyOrders']);
Route::middleware('auth:sanctum')->get('/getMyOrdersStatus/{status}', [ordersController::class, 'getMyOrdersStatus']);

Route::middleware('auth:sanctum')->get('/getDriverPendingOrders', [ordersController::class, 'getDriverPendingOrders']);
Route::middleware('auth:sanctum')->get('/getDriverOrders', [ordersController::class, 'getDriverOrders']);
Route::middleware('auth:sanctum')->get('/getDriverShipped', [ordersController::class, 'getDriverShipped']);
Route::middleware('auth:sanctum')->get('/getDriverDelivered', [ordersController::class, 'getDriverDelivered']);
Route::middleware('auth:sanctum')->put('/acceptOrder/{id}', [ordersController::class, 'acceptOrder']);
Route::middleware('auth:sanctum')->put('/rejectOrder/{id}', [ordersController::class, 'rejectOrder']);
Route::middleware('auth:sanctum')->put('/confirmDelivery/{id}', [ordersController::class, 'confirmDelivery']);
Route::middleware('auth:sanctum')->put('/confirmPickup/{id}', [ordersController::class, 'confirmPickup']);
Route::middleware('auth:sanctum')->get('/getMyShippedOrders', [ordersController::class, 'getMyShippedOrders']);
Route::middleware('auth:sanctum')->get('/getMyDeliveredOrders', [ordersController::class, 'getMyDeliveredOrders']);
Route::middleware('auth:sanctum')->get('/getMyPendingOrders', [ordersController::class, 'getMyPendingOrders']);
Route::middleware('auth:sanctum')->put('/repurchaseOrder/{id}', [ordersController::class, 'repurchaseOrder']);
Route::middleware('auth:sanctum')->get('/getWarehouseOrders', [ordersController::class, 'getWarehouseOrders']);
Route::middleware('auth:sanctum')->get('/getOrderDetails/{id}', [ordersController::class, 'getOrderDetails']);
/////////////////////////////////////whishlists///////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->put('/addToWishlist/{id}', [wishlistsController::class, 'addToWishlist']);
Route::middleware('auth:sanctum')->get('/getMyWishlist', [wishlistsController::class, 'getMyWishlist']);
Route::middleware('auth:sanctum')->delete('/removeFromWishlist/{id}', [wishlistsController::class, 'removeFromWishlist']);
///////////////////////////////////resources///////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->resource('/categories', categoriesController::class);
Route::middleware('auth:sanctum')->resource('/products', productsController::class);
Route::middleware('auth:sanctum')->resource('/addressBooks', addressBooksController::class);
Route::middleware('auth:sanctum')->resource('/credits', creditsController::class);
////////////////////////////////////Products////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/productsByCategory/{id}', [productsController::class, 'productsByCategory']);
Route::middleware('auth:sanctum')->post('/searchItems', [productsController::class, 'searchItems']);
Route::middleware('auth:sanctum')->get('/getFeatured', [productsController::class, 'getFeatured']);
////////////////////////////////////Categories//////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMainCategories', [categoriesController::class, 'getMainCategories']);
Route::middleware('auth:sanctum')->get('/getSubCategories', [categoriesController::class, 'getSubCategories']);
Route::middleware('auth:sanctum')->get('/showSubCategories/{id}', [categoriesController::class, 'showSubCategories']);
Route::middleware('auth:sanctum')->get('/getImediateSubCat/{id}', [categoriesController::class, 'getImediateSubCat']);
///////////////////////////////////cart/////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/addToCart', [cartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->get('/getMyCart', [cartController::class, 'getMyCart']);
Route::middleware('auth:sanctum')->post('/updateCart', [cartController::class, 'updateCart']);
//////////////////////////////////////////auth////////////////////////////////
Route::post('/getUserToken', [getTokenController::class, 'getUserToken']);
Route::post('/getDriverToken', [getTokenController::class, 'getDriverToken']);
Route::post('/getAgentToken', [getTokenController::class, 'getAgentToken']);
Route::post('/getWarehouseToken', [getTokenController::class, 'getWarehouseToken']);
Route::post('/registerUser', [registerUsersController::class, 'registerUser']);
Route::post('/registerAgent', [registerUsersController::class, 'registerAgent']);
//////////////////////////////////////addressbook//////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyAddresses', [addressBooksController::class, 'getMyAddresses']);
///////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
