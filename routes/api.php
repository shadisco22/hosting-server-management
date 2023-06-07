<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin;
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
Route::get('/login', function(){
    return response()->json("Not authenticated", 401);
})->name("login");
Route::post('/register', [Auth::class, 'register']);
Route::post('/login', [Auth::class, 'login']);
Route::post('/logout', [Auth::class, 'logout'])->middleware('auth:sanctum');

// Customer routes
Route::group(['middleware' => ['auth:sanctum', 'checkRole:Customer']], function () {
    //Route::post('customer/add-company', [\App\Http\Controllers\CustomerController::class, 'addCompany']);

    Route::post('customer/pay', [OrderController::class, 'pay'])->name('pay');
    Route::get('customer/success', [OrderController::class, 'success']);
    Route::get('customer/error', [OrderController::class, 'error']);
    Route::get("customer/showpackages", [HostingPlanController::class, 'index']);
    Route::post("customer/orderpackage", [OrderController::class, 'store']);
    Route::put("customer/editprofile/{id}", [CustomerController::class, 'update']);
});

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'checkRole:Admin']], function () {


    Route::post('admin/createoperator', [Admin::class, 'createOperator']);
    Route::delete("admin/deleteoperator/{id}", [Admin::class, 'destroy']);
    Route::get('admin/showusers', [Admin::class, 'show']);

    Route::post("admin/addpackage", [HostingPlanController::class, 'store']);
    Route::delete("admin/deletepackage/{id}", [HostingPlanController::class, 'destroy']);
    Route::put("admin/updatepackage/{id}", [HostingPlanController::class, 'update']);
    Route::get("admin/showpackages", [HostingPlanController::class, 'index']);
    Route::delete("admin/deletecustomer/{id}", [CustomerController::class, 'destroy']);
    Route::get("admin/showorders/{id}", [OrderController::class, 'index']);
    Route::get("admin/showorders", [OrderController::class, 'index']);
});

// Operator routes
Route::group(['middleware' => ['auth:sanctum', 'checkRole:Operator']], function () {

    Route::get("operator/showorders", [OrderController::class, 'index']);
    Route::get("operator/showorders/{id}", [OrderController::class, 'index']);
});
