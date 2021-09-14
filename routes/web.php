<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class, 'showlogin']);
Route::post('/login', [LoginController::class, 'login']);
Route::view('reset-password', 'reset-password');
Route::post('/sendForgotEmail', [LoginController::class, 'sendForgotEmail']);
Route::get('/change-password/{email}', [LoginController::class, 'changePassword']);
Route::post('ChangePassword', [LoginController::class, 'ChangePasswordPro']);

Route::group(['middleware' => ["CheckUser"]], function () {
    Route::get('/dashboard', [AdminController::class, 'index']);

    Route::group(['middleware' => ['CheckAdmin']], function () {
        Route::get('/clients', [AdminController::class, 'clients']);
        Route::post('/client', [AdminController::class, 'addclient']);
        Route::get('/delete-client/{client_id}', [AdminController::class, 'deleteClient']);
        Route::get('/edit-client/{client_id}', [AdminController::class, 'editClient']);
        Route::post('/editClient', [AdminController::class, 'editClientProcess']);
    });

    Route::get('/brands', [AdminController::class, 'showbrand']);
    Route::post('/brand', [AdminController::class, 'brand']);
    Route::get('/deletebrand/{id}', [AdminController::class, 'deletebrand']);
    Route::get('/editbrand/{id}', [AdminController::class, 'showeditbrand']);
    Route::post('/updatebrand', [AdminController::class, 'editbrand']);

    Route::get('/category', [AdminController::class, 'category']);
    Route::post('/category', [AdminController::class, 'addcategory']);
    Route::get('/deletecategory/{id}', [AdminController::class, 'deletecategory']);
    Route::get('/editcategory/{id}', [AdminController::class, 'editcategory']);
    Route::post('/editcategory', [AdminController::class, 'updatecategory']);

    Route::get('/products', [AdminController::class, 'products']);
    Route::get('/getCategory/{id}', [AdminController::class, 'getCategory']);
    Route::post('/product', [AdminController::class, 'addProduct']);
    Route::get('/deleteproduct/{id}', [AdminController::class, 'deleteproduct']);
    Route::get('/editproduct/{id}', [AdminController::class, 'editproduct']);
    Route::post('/updateproduct', [AdminController::class, 'updateproduct']);

    Route::get('/gst-on-bill', [AdminController::class, 'gstonbill']);
    Route::post('/addgst', [AdminController::class, 'addgst']);
    Route::get('/deleteGST/{id}', [AdminController::class, 'deleteGST']);

    Route::get('/order', [AdminController::class, 'order']);
    Route::post('/addItem', [AdminController::class, 'addItem']);
    Route::get('/getorders', [AdminController::class, 'getorders']);
    Route::get('/removeItem/{product_id}', [AdminController::class, 'removeItem']);
    Route::post('/changeQuantity', [AdminController::class, 'changeQuantity']);

    Route::post('addCustomer', [AdminController::class, 'addCustomer']);
    Route::get('/addCustomerToOrder/{customer_id}', [AdminController::class, 'addCustomerToOrder']);

    Route::get('/invoice/{order_id}', [AdminController::class, 'invoice']);
    Route::get('/changeStatusOfOrder', [AdminController::class, 'changeStatusOfOrder']);
    Route::get('/updateTotalBalance/{order_id}/{amount}/{discount}', [AdminController::class, 'updateTotalBalance']);
    Route::get('/balance/{balance}', [AdminController::class, 'balance']);

    Route::get('/orderHistory', [AdminController::class, 'orderHistory']);
    Route::get('/view-invoice/{order_id}', [AdminController::class, 'viewInvoice']);

    Route::get('/customers', [AdminController::class, 'showCustomers']);
    Route::get('/edit-customer/{customer_id}', [AdminController::class, 'editCustomer']);
    Route::post('/updateCustomer', [AdminController::class, 'updateCustomer']);

    Route::get('/logout', [LoginController::class, 'logout']);
});
Route::get('/historyofday', [AdminController::class, 'todayOrderHistory']);
Route::post('/orderHistoryInPdf', [AdminController::class, 'test']);
