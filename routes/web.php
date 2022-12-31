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
use App\Http\Controllers\creditsController;
use App\Http\Controllers\locationsController;
use App\Http\Controllers\visitsController;
use App\Http\Controllers\salesDashController;

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
Route::middleware('auth:sanctum')->get('/getRouteDrivers/{route_id}', [driversController::class, 'getRouteDrivers']);
Route::middleware('auth:sanctum')->get('/getDriversRaw', [driversController::class, 'getDriversRaw']);
/////////////////////////////////////shop//////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getShops', [shopsController::class, 'getShops']);
Route::middleware('auth:sanctum')->get('/getShopsWithNoRoutes', [shopsController::class, 'getShopsWithNoRoutes']);
Route::middleware('auth:sanctum')->get('/shopDetails/{id}', [shopsController::class, 'shopDetails']);
Route::middleware('auth:sanctum')->get('/shopOrders/{id}', [shopsController::class, 'shopOrders']);
Route::middleware('auth:sanctum')->get('/shopLocations/{id}', [shopsController::class, 'shopLocations']);
Route::middleware('auth:sanctum')->put('/verfiyShop/{id}', [shopsController::class, 'verfiyShop']);
Route::middleware('auth:sanctum')->put('/addShop/{id}', [shopsController::class, 'addShop']);
Route::middleware('auth:sanctum')->put('/updateShop/{id}', [shopsController::class, 'updateShop']);
Route::middleware('auth:sanctum')->post('/searchCustomer', [shopsController::class, 'searchCustomer']);
Route::middleware('auth:sanctum')->post('/assignSalesManager', [shopsController::class, 'assignSalesManager']);
Route::middleware('auth:sanctum')->put('/updateSalesManager/{id}', [shopsController::class, 'updateSalesManager']);
Route::middleware('auth:sanctum')->post('/exportCustomers', [shopsController::class, 'exportCustomers']);
Route::middleware('auth:sanctum')->put('/banShop/{id}', [shopsController::class, 'banShop']);
Route::middleware('auth:sanctum')->put('/unbanShop/{id}', [shopsController::class, 'unbanShop']);
Route::middleware('auth:sanctum')->post('/exportCustomerOrders', [shopsController::class, 'exportCustomerOrders']);
/////////////////////////////////////locations//////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getCountries', [locationsController::class, 'getCountries']);
Route::middleware('auth:sanctum')->get('/getCities', [locationsController::class, 'getCities']);
Route::middleware('auth:sanctum')->get('/getSubCities', [locationsController::class, 'getSubCities']);
Route::middleware('auth:sanctum')->post('/addCountry', [locationsController::class, 'addCountry']);
Route::middleware('auth:sanctum')->post('/addCity', [locationsController::class, 'addCity']);
Route::middleware('auth:sanctum')->post('/addSubCity', [locationsController::class, 'addSubCity']);
Route::middleware('auth:sanctum')->put('/updateSubCity/{id}', [locationsController::class, 'updateSubCity']);
Route::middleware('auth:sanctum')->put('/updateCountry/{id}', [locationsController::class, 'updateCountry']);
Route::middleware('auth:sanctum')->put('/updateCity/{id}', [locationsController::class, 'updateCity']);
Route::middleware('auth:sanctum')->delete('/deleteSubCity/{id}', [locationsController::class, 'deleteSubCity']);
Route::middleware('auth:sanctum')->delete('/deleteCity/{id}', [locationsController::class, 'deleteCity']);
Route::middleware('auth:sanctum')->delete('/deleteCountry/{id}', [locationsController::class, 'deleteCountry']);
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
Route::middleware('auth:sanctum')->post('/filterZones', [zonesController::class, 'filterZones']);
Route::middleware('auth:sanctum')->post('/exportZones', [zonesController::class, 'exportZones']);
Route::middleware('auth:sanctum')->delete('/deleteZone/{id}', [zonesController::class, 'deleteZone']);
///////////////////////////////////routes//////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/filterRoutes', [routesController::class, 'filterRoutes']);
Route::middleware('auth:sanctum')->post('/exportRoutes', [routesController::class, 'exportRoutes']);
Route::middleware('auth:sanctum')->get('/getRoute/{id}', [routesController::class, 'getRoute']);
Route::middleware('auth:sanctum')->get('/getRoutesRaw', [routesController::class, 'getRoutesRaw']);
///////////////////////////////////resources///////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->resource('/categories', categoriesController::class);
Route::middleware('auth:sanctum')->resource('/warehouses', warehousesController::class);
Route::middleware('auth:sanctum')->resource('/products', productsController::class);
Route::middleware('auth:sanctum')->resource('/orders', ordersController::class);
Route::middleware('auth:sanctum')->resource('/credits', creditsController::class);
Route::resource('/addressBooks', addressBooksController::class);
Route::middleware('auth:sanctum')->resource('/drivers', driversController::class);
Route::middleware('auth:sanctum')->resource('/routes', routesController::class);
Route::middleware('auth:sanctum')->resource('/visits', visitsController::class);
///////////////////////////////////Notifications/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyNotifications', [notificationsController::class, 'getMyNotifications']);
Route::middleware('auth:sanctum')->get('/markAllAsRead', [notificationsController::class, 'markAllAsRead']);
Route::middleware('auth:sanctum')->post('/broadcastVisitLocation/{id}', [visitsController::class, 'broadcastVisitLocation']);
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
Route::middleware('auth:sanctum')->get('/getWarehouseOrders', [ordersController::class, 'getWarehouseOrders']);
Route::middleware('auth:sanctum')->get('/getPendingConfirmation', [ordersController::class, 'getPendingConfirmation']);
Route::middleware('auth:sanctum')->get('/getPendingPickup', [ordersController::class, 'getPendingPickup']);
Route::middleware('auth:sanctum')->post('/filterOrders', [ordersController::class, 'filterOrders']);
Route::middleware('auth:sanctum')->post('/exportOrders', [ordersController::class, 'exportOrders']);
Route::middleware('auth:sanctum')->get('/getOrderWarehouse/{id}', [ordersController::class, 'getOrderWarehouse']);
Route::middleware('auth:sanctum')->put('/confirmDelivery/{id}', [ordersController::class, 'confirmDelivery']);
///////////////////////////////////products/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/productsByCategory/{id}', [productsController::class, 'productsByCategory']);
Route::middleware('auth:sanctum')->post('/uploadProductPic', [productsController::class, 'uploadProductPic']);
Route::middleware('auth:sanctum')->post('/updateProductPic', [productsController::class, 'updateProductPic']);
Route::middleware('auth:sanctum')->get('/getProductsList', [productsController::class, 'getProductsList']);
Route::middleware('auth:sanctum')->post('/deleteProductPic', [productsController::class, 'deleteProductPic']);
Route::middleware('auth:sanctum')->post('/saveDraft', [productsController::class, 'saveDraft']);
Route::middleware('auth:sanctum')->post('/searchItems', [productsController::class, 'searchItems']);
Route::middleware('auth:sanctum')->put('/toggleFeature/{id}', [productsController::class, 'toggleFeature']);
Route::middleware('auth:sanctum')->get('/getProductWarehouses/{id}', [productsController::class, 'getProductWarehouses']);
Route::middleware('auth:sanctum')->get('/getPriceRange', [productsController::class, 'getPriceRange']);
Route::middleware('auth:sanctum')->post('/getProductsList', [productsController::class, 'filterProducts']);
Route::middleware('auth:sanctum')->post('/exportProducts', [productsController::class, 'exportProducts']);
Route::middleware('auth:sanctum')->get('/getDeletedProducts', [productsController::class, 'getDeletedProducts']);
Route::middleware('auth:sanctum')->put('/restoreProduct/{id}', [productsController::class, 'restoreProduct']);
Route::middleware('auth:sanctum')->get('/getDeletedWarehouses', [warehousesController::class, 'getDeletedWarehouses']);
Route::middleware('auth:sanctum')->put('/restoreWarehouse/{id}', [warehousesController::class, 'restoreWarehouse']);
///////////////////////////////////SalesAnalytics/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/bestSeller', [salesDashController::class, 'bestSeller']);
Route::middleware('auth:sanctum')->post('/productsRank', [salesDashController::class, 'productsRank']);
Route::middleware('auth:sanctum')->get('/worstSeller', [salesDashController::class, 'worstSeller']);
Route::middleware('auth:sanctum')->post('/getCustomerRank', [salesDashController::class, 'getCustomersRank']);
Route::middleware('auth:sanctum')->post('/getAgentsRank', [salesDashController::class, 'getAgentsRank']);
Route::middleware('auth:sanctum')->post('/getRTMrank', [salesDashController::class, 'getRTMrank']);
///////////////////////////////////agents/////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getAgents', [agentsController::class, 'getAgents']);
Route::middleware('auth:sanctum')->get('/agentDetails/{id}', [agentsController::class, 'agentDetails']);
Route::middleware('auth:sanctum')->get('/agentOrders/{id}', [agentsController::class, 'agentOrders']);
Route::middleware('auth:sanctum')->post('/placeRequest', [agentsController::class, 'placeRequest']);
Route::middleware('auth:sanctum')->get('/getPaymentRequests', [agentsController::class, 'getPaymentRequests']);
Route::middleware('auth:sanctum')->get('/showPaymentRequest/{id}', [agentsController::class, 'showPaymentRequest']);
Route::middleware('auth:sanctum')->get('/getMyRequests', [agentsController::class, 'getMyRequests']);
Route::middleware('auth:sanctum')->put('/confirmRequest/{id}', [agentsController::class, 'confirmRequest']);
Route::middleware('auth:sanctum')->put('/verfiyAgent/{id}', [agentsController::class, 'verfiyAgent']);
/////////////////////////////////Categories//////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getSubCategories', [categoriesController::class, 'getSubCategories']);
Route::middleware('auth:sanctum')->get('/chooseSubCategories', [categoriesController::class, 'chooseSubCategories']);
Route::middleware('auth:sanctum')->get('/getNodeCategories/{id}', [categoriesController::class, 'getNodeCategories']);
Route::middleware('auth:sanctum')->get('/getMainCategories', [categoriesController::class, 'getMainCategories']);
Route::middleware('auth:sanctum')->post('/uploadSubPic', [categoriesController::class, 'uploadSubPic']);
Route::middleware('auth:sanctum')->get('/getImediateSubCat/{id}', [categoriesController::class, 'getImediateSubCat']);
Route::middleware('auth:sanctum')->post('/makeChild', [categoriesController::class, 'makeChild']);
Route::middleware('auth:sanctum')->get('/getAllNodeCategories', [categoriesController::class, 'getAllNodeCategories']);
Route::middleware('auth:sanctum')->post('/filterCategories', [categoriesController::class, 'filterCategories']);
Route::middleware('auth:sanctum')->post('/exportCategories', [categoriesController::class, 'exportCategories']);
Route::middleware('auth:sanctum')->get('/getDeletedMainCategories', [categoriesController::class, 'getDeletedMainCategories']);
Route::middleware('auth:sanctum')->get('/getDeletedSubCategories', [categoriesController::class, 'getDeletedSubCategories']);
Route::middleware('auth:sanctum')->put('/restoreCategory/{id}', [categoriesController::class, 'restoreCategory']);
//////////////////////////////////////addressbook//////////////////////////////////////////////////
Route::middleware('auth:sanctum')->get('/getMyAddresses', [addressBooksController::class, 'getMyAddresses']);
///////////////////////////////////staff/////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->post('/registerStaff', [staffController::class, 'registerStaff']);
Route::middleware('auth:sanctum')->post('/searchStaff', [staffController::class, 'searchStaff']);
Route::middleware('auth:sanctum')->get('/getStaff', [staffController::class, 'getStaff']);
Route::middleware('auth:sanctum')->get('/showStaff/{id}', [staffController::class, 'showStaff']);
Route::middleware('auth:sanctum')->put('/editStaff/{id}', [staffController::class, 'editStaff']);
Route::middleware('auth:sanctum')->post('/resetStaffPass', [staffController::class, 'resetStaffPass']);
Route::middleware('auth:sanctum')->get('/getStaffPermissions', [staffController::class, 'getStaffPermissions']);
Route::middleware('auth:sanctum')->get('/getWarehouseManagers', [staffController::class, 'getWarehouseManagers']);
Route::middleware('auth:sanctum')->post('/filterWarehouses', [warehousesController::class, 'filterWarehouses']);
Route::middleware('auth:sanctum')->post('/exportWarehouses', [warehousesController::class, 'exportWarehouses']);
Route::middleware('auth:sanctum')->post('/addWarehouseManager', [warehousesController::class, 'addWarehouseManager']);
Route::middleware('auth:sanctum')->put('/updateWarehouseManager/{id}', [warehousesController::class, 'updateWarehouseManager']);
Route::middleware('auth:sanctum')->get('/getSalesManagers', [staffController::class, 'getSalesManagers']);
Route::middleware('auth:sanctum')->post('/goOnline', [staffController::class, 'goOnline']);
Route::middleware('auth:sanctum')->post('/goOffline', [staffController::class, 'goOffline']);
Route::middleware('auth:sanctum')->delete('/deleteStaff/{id}', [staffController::class, 'deleteStaff']);
Route::middleware('auth:sanctum')->delete('/deleteWarehouseManager/{id}', [warehousesController::class, 'deleteWarehouseManager']);
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
Route::middleware(['check-permission'])->any('{slug}', function () {
    return view('home');
});
Route::any('/orderDetails/{slug}', function () {
    return view('home');
});
Route::any('/visitDetails/{slug}', function () {
    return view('home');
});
Route::any('/customerDetails/{slug}', function () {
    return view('home');
});
Route::any('/agentDetail/{slug}', function () {
    return view('home');
});
Route::any('/editProduct/{slug}', function () {
    return view('home');
});
Route::any('/shippingDetails/{slug}', function () {
    return view('home');
});
Route::any('requestDetails/{slug}', function () {
    return view('home');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
