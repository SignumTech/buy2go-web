<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\productsController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\addressBookController;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\warehousesController;
use App\Http\Controllers\addressBooksController;
use App\Http\Controllers\zonesController;
use App\Http\Controllers\staffController;
use App\Http\Controllers\rolePermissionController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\driversController;
use App\Http\Controllers\notificationsController;
use App\Http\Controllers\routesController;
use App\Http\Controllers\shopsController;
use App\Http\Controllers\agentsController;
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
/////////////////////////////////////drivers//////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getZoneDrivers/{zone_id}', [driversController::class, 'getZoneDrivers']);
/////////////////////////////////////shop//////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getShops', [shopsController::class, 'getShops']);
Route::middleware('auth:sanctum')->get('/getShopsWithNoRoutes', [shopsController::class, 'getShopsWithNoRoutes']);
/////////////////////////////////////dashboard////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/salesThirty', [dashboardController::class, 'salesThirty']);
Route::middleware('auth:sanctum')->get('/salesToday', [dashboardController::class, 'salesToday']);
Route::middleware('auth:sanctum')->get('/ordersThirty', [dashboardController::class, 'ordersThirty']);
Route::middleware('auth:sanctum')->get('/ordersToday', [dashboardController::class, 'ordersToday']);
Route::middleware('auth:sanctum')->get('/usersThirty', [dashboardController::class, 'usersThirty']);
Route::middleware('auth:sanctum')->get('/getOrdersByGroup', [dashboardController::class, 'getOrdersByGroup']);
Route::middleware('auth:sanctum')->get('/ordersSeven', [dashboardController::class, 'ordersSeven']);
Route::middleware('auth:sanctum')->get('/salesSeven', [dashboardController::class, 'salesSeven']);
Route::middleware('auth:sanctum')->get('/revenueYear', [dashboardController::class, 'revenueYear']);
Route::middleware('auth:sanctum')->post('/salesReport', [dashboardController::class, 'salesReport']);
///////////////////////////////////zones///////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getZones', [zonesController::class, 'getZones']);
Route::middleware('auth:sanctum')->post('/addZones', [zonesController::class, 'addZones']);
Route::middleware('auth:sanctum')->put('/updateZones/{id}', [zonesController::class, 'updateZones']);
///////////////////////////////////resources///////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->resource('/categories', categoriesController::class);
Route::middleware('auth:sanctum')->resource('/warehouses', warehousesController::class);
Route::resource('/products', productsController::class);
Route::middleware('auth:sanctum')->resource('/orders', ordersController::class);
Route::resource('/addressBooks', addressBooksController::class);
Route::middleware('auth:sanctum')->resource('/drivers', driversController::class);
Route::middleware('auth:sanctum')->resource('/routes', routesController::class);
///////////////////////////////////Notifications/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyNotifications', [notificationsController::class, 'getMyNotifications']);
///////////////////////////////////orders/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getProcessing', [ordersController::class, 'getProcessing']);
Route::middleware('auth:sanctum')->get('/getShipped', [ordersController::class, 'getShipped']);
Route::middleware('auth:sanctum')->get('/getDelivered', [ordersController::class, 'getDelivered']);
Route::middleware('auth:sanctum')->put('/assignDetails/{id}', [ordersController::class, 'assignDetails']);
Route::middleware('auth:sanctum')->get('/getDriverPendingOrders', [ordersController::class, 'getDriverPendingOrders']);
Route::middleware('auth:sanctum')->get('/getDriverOrders', [ordersController::class, 'getDriverOrders']);
Route::middleware('auth:sanctum')->get('/acceptOrder/{id}', [ordersController::class, 'acceptOrder']);
Route::middleware('auth:sanctum')->get('/rejectOrder/{id}', [ordersController::class, 'rejectOrder']);
Route::middleware('auth:sanctum')->get('/getOrderDetails/{id}', [ordersController::class, 'getOrderDetails']);
///////////////////////////////////products/////////////////////////////////////////////////////////
Route::get('/productsByCategory/{id}', [productsController::class, 'productsByCategory']);
Route::post('/uploadProductPic', [productsController::class, 'uploadProductPic']);
Route::post('/updateProductPic', [productsController::class, 'updateProductPic']);
Route::get('/getProductsList', [productsController::class, 'getProductsList']);
Route::middleware('auth:sanctum')->post('/deleteProductPic', [productsController::class, 'deleteProductPic']);
Route::middleware('auth:sanctum')->post('/saveDraft', [productsController::class, 'saveDraft']);
Route::middleware('auth:sanctum')->post('/searchItems', [productsController::class, 'searchItems']);
///////////////////////////////////agents/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getAgents', [agentsController::class, 'getAgents']);
/////////////////////////////////Categories//////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getSubCategories', [categoriesController::class, 'getSubCategories']);
Route::middleware('auth:sanctum')->get('/chooseSubCategories', [categoriesController::class, 'chooseSubCategories']);
Route::middleware('auth:sanctum')->get('/getNodeCategories', [categoriesController::class, 'getNodeCategories']);
Route::get('/getMainCategories', [categoriesController::class, 'getMainCategories']);
Route::middleware('auth:sanctum')->post('/uploadSubPic', [categoriesController::class, 'uploadSubPic']);
Route::get('/getImediateSubCat/{id}', [categoriesController::class, 'getImediateSubCat']);
Route::middleware('auth:sanctum')->post('/makeChild', [categoriesController::class, 'makeChild']);
//////////////////////////////////////addressbook//////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyAddresses', [addressBooksController::class, 'getMyAddresses']);
///////////////////////////////////staff/////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/registerStaff', [staffController::class, 'registerStaff']);
Route::middleware('auth:sanctum')->post('/searchStaff', [staffController::class, 'searchStaff']);
Route::middleware('auth:sanctum')->get('/getStaff', [staffController::class, 'getStaff']);
Route::middleware('auth:sanctum')->get('/showStaff/{id}', [staffController::class, 'showStaff']);
Route::middleware('auth:sanctum')->post('/editStaff', [staffController::class, 'editStaff']);
Route::middleware('auth:sanctum')->post('/resetStaffPass', [staffController::class, 'resetStaffPass']);
Route::middleware('auth:sanctum')->get('/getStaffPermissions', [staffController::class, 'getStaffPermissions']);
////////////////////////////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/createRolePermission', [rolePermissionController::class, 'createRolePermission']);
Route::middleware('auth:sanctum')->post('/updateRolePermission', [rolePermissionController::class, 'updateRolePermission']);
Route::middleware('auth:sanctum')->get('/getRoles', [rolePermissionController::class, 'getRoles']);
Route::middleware('auth:sanctum')->get('/getRoles/{id}', [rolePermissionController::class, 'showRole']);
Route::middleware('auth:sanctum')->delete('/deleteRole/{id}', [rolePermissionController::class, 'deleteRole']);
///////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/', function () {
    return view('home');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Auth::routes();
Route::any('{slug}', function () {
    return view('home');
});
Route::any('/orderDetails/{slug}', function () {
    return view('home');
});
Route::any('/shippingDetails/{slug}', function () {
    return view('home');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
