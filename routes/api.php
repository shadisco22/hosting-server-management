<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HostingPlanController;
use App\Http\Controllers\OrderController;
use App\Models\hostingPlan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Login/register routes

Route::post('/register', [Auth::class, 'register']);
Route::post('/login', [Auth::class, 'login']);
Route::post('/logout', [Auth::class, 'logout'])->middleware('auth:sanctum');

// Customer routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'checkRole:Customer'], function () {
        Route::post('customer/add-company', [\App\Http\Controllers\CustomerController::class, 'addCompany']);

        Route::post('customer/pay', [OrderController::class, 'pay'])->name('pay');
        Route::get('customer/success', [OrderController::class, 'success']);
        Route::get('customer/error', [OrderController::class, 'error']);
        Route::get("customer/showpackages", [HostingPlanController::class, 'index']);
        Route::post("customer/orderpackage", [OrderController::class, 'store']);
    });
});

// Admin routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'checkRole:Admin'], function () {

        Route::post('admin/create-operater', [\App\Http\Controllers\Admin::class, 'createOperater']);
        Route::get('admin/show-operater', [\App\Http\Controllers\Admin::class, 'show']);

        Route::post("admin/addpackage", [HostingPlanController::class, 'store']);
        Route::delete("admin/deletepackage/{id}", [HostingPlanController::class, 'destroy']);
        Route::post("admin/updatepackage/{id}", [HostingPlanController::class, 'update']);
        Route::get("admin/showpackages", [HostingPlanController::class, 'index']);
        Route::get("admin/showcustomers", [CustomerController::class, 'index']);
        Route::delete("admin/deletecustomer/{id}", [CustomerController::class, 'destroy']);
        Route::get("admin/showorders/{id}", [OrderController::class, 'index']);
        Route::get("admin/showorders", [OrderController::class, 'index']);
    });
});

// Operator routes
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'checkRole:Operater'], function () {

        Route::get("operator/showorders", [OrderController::class, 'index']);
        Route::get("operator/showorders/{id}", [OrderController::class, 'index']);
    });
});
