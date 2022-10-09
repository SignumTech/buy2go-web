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
///////////////////////////////////zones///////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getZones', [zonesController::class, 'getZones']);
Route::middleware('auth:sanctum')->post('/addZones', [zonesController::class, 'addZones']);
Route::middleware('auth:sanctum')->put('/updateZones/{id}', [zonesController::class, 'updateZones']);
///////////////////////////////////resources///////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->resource('/categories', categoriesController::class);
Route::middleware('auth:sanctum')->resource('/warehouses', warehousesController::class);
Route::resource('/products', productsController::class);
Route::middleware('auth:sanctum')->resource('/orders', ordersController::class);
Route::resource('/addressBooks', addressBookController::class);
///////////////////////////////////orders/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getProcessing', [ordersController::class, 'getProcessing']);
Route::middleware('auth:sanctum')->get('/getShipped', [ordersController::class, 'getShipped']);
Route::middleware('auth:sanctum')->get('/getDelivered', [ordersController::class, 'getDelivered']);
///////////////////////////////////products/////////////////////////////////////////////////////////
Route::get('/productsByCategory/{id}', [productsController::class, 'productsByCategory']);
Route::post('/uploadProductPic', [productsController::class, 'uploadProductPic']);
Route::post('/updateProductPic', [productsController::class, 'updateProductPic']);
Route::get('/getProductsList', [productsController::class, 'getProductsList']);
Route::middleware('auth:sanctum')->post('/deleteProductPic', [productsController::class, 'deleteProductPic']);
Route::middleware('auth:sanctum')->post('/saveDraft', [productsController::class, 'saveDraft']);
/////////////////////////////////Categories//////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getSubCategories', [categoriesController::class, 'getSubCategories']);
Route::middleware('auth:sanctum')->get('/chooseSubCategories', [categoriesController::class, 'chooseSubCategories']);
Route::middleware('auth:sanctum')->get('/getNodeCategories', [categoriesController::class, 'getNodeCategories']);
Route::get('/getMainCategories', [categoriesController::class, 'getMainCategories']);
Route::middleware('auth:sanctum')->post('/uploadSubPic', [categoriesController::class, 'uploadSubPic']);
Route::get('/getImediateSubCat/{id}', [categoriesController::class, 'getImediateSubCat']);
//////////////////////////////////////addressbook//////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyAddresses', [addressBooksController::class, 'getMyAddresses']);

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
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
