<?php

use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\CashflowController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\ItemController;
use App\Http\Controllers\admin\PurchaseController;
use App\Http\Controllers\admin\PurchaseReturController;
use App\Http\Controllers\admin\SalesController;
use App\Http\Controllers\admin\StockController;
use App\Http\Controllers\admin\SupplierController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\UdahLogin;
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


Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);

Route::get('/login', [AutentikasiController::class, 'index']);
Route::post('/login_submit', [AutentikasiController::class, 'login_submit']);
Route::get('/logout', [AutentikasiController::class, 'logout']);



Route::middleware([UdahLogin::class])->group(function () {
    Route::get('/admin/', [AdminHomeController::class, 'index']);
    Route::get('/admin/home', [AdminHomeController::class, 'index']);

    Route::get('/admin/password', [UserController::class, 'index']);
    Route::post('/admin/password/submit', [UserController::class, 'submit']);

    Route::get('/admin/category', [CategoryController::class, 'index']);
    Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit']);
    Route::get('/admin/category/add', [CategoryController::class, 'edit']);
    Route::post('/admin/category/submit/', [CategoryController::class, 'submit']);
    Route::get('/admin/category/delete/{id}', [CategoryController::class, 'delete']);

    Route::get('/admin/item', [ItemController::class, 'index']);
    Route::get('/admin/item/edit/{id}', [ItemController::class, 'edit']);
    Route::get('/admin/item/add/', [ItemController::class, 'edit']);
    Route::post('/admin/item/submit/', [ItemController::class, 'submit']);
    Route::get('/admin/item/delete/{id}', [ItemController::class, 'delete']);

    Route::get('/admin/item/datatables/', [ItemController::class, 'datatables']);
    Route::get('/admin/item/get_item/{id}', [ItemController::class, 'get_item']);

    Route::get('/admin/supplier/', [SupplierController::class, 'index']);
    Route::get('/admin/supplier/edit/{id}', [SupplierController::class, 'edit']);
    Route::get('/admin/supplier/add', [SupplierController::class, 'edit']);
    Route::post('/admin/supplier/submit', [SupplierController::class, 'submit']);
    Route::get('/admin/supplier/delete/{id}', [SupplierController::class, 'delete']);


    Route::get('/admin/customer/', [CustomerController::class, 'index']);
    Route::get('/admin/customer/edit/{id}', [CustomerController::class, 'edit']);
    Route::get('/admin/customer/add', [CustomerController::class, 'edit']);
    Route::post('/admin/customer/submit', [CustomerController::class, 'submit']);
    Route::get('/admin/customer/delete/{id}', [CustomerController::class, 'delete']);


    Route::get('/admin/stock/', [StockController::class, 'index']);
    Route::get('/admin/stock/detail/{id}', [StockController::class, 'stock_detail']);
    Route::get('/admin/stock/adj', [StockController::class, 'adj']);
    Route::post('/admin/stock/adj_submit', [StockController::class, 'adj_submit']);

    Route::get('/admin/purchase/', [PurchaseController::class, 'index']);
    Route::get('/admin/purchase/add/', [PurchaseController::class, 'add']);
    Route::post('/admin/purchase/submit/', [PurchaseController::class, 'submit']);
    Route::get('/admin/purchase/delete/{id}', [PurchaseController::class, 'delete']);
    Route::get('/admin/purchase/edit/{id}', [PurchaseController::class, 'edit']);
    Route::post('/admin/purchase/edit_submit/', [PurchaseController::class, 'edit_submit']);
    Route::get('/admin/purchase/view/{id}', [PurchaseController::class, 'view']);

    Route::get('/admin/sales/', [SalesController::class, 'index']);
    Route::get('/admin/sales/add', [SalesController::class, 'add']);
    Route::post('/admin/sales/submit', [SalesController::class, 'submit']);
    Route::get('/admin/sales/edit/{id}', [SalesController::class, 'edit']);
    Route::post('/admin/sales/edit_submit/', [SalesController::class, 'edit_submit']);
    Route::get('/admin/sales/view/{id}', [SalesController::class, 'view']);
    Route::get('/admin/sales/delete/{id}', [SalesController::class, 'delete']);


    Route::get('/admin/purchase_retur/', [PurchaseReturController::class, 'index']);
    Route::get('/admin/purchase_retur/add', [PurchaseReturController::class, 'add']);
    Route::get('/admin/purchase_retur/purchase_dtt', [PurchaseReturController::class, 'purchase_dtt']);
    Route::get('/admin/purchase_retur/purchase_detail/{id}', [PurchaseReturController::class, 'purchase_detail']);
    Route::get('/admin/purchase_retur/view/{id}', [PurchaseReturController::class, 'view']);
    Route::post('/admin/purchase_retur/submit', [PurchaseReturController::class, 'submit']);



    
    Route::get('/admin/cashflow/', [CashflowController::class, 'index']);
    // Route::get('/admin/cashflow/add', [CashflowController::class, 'add']);


});