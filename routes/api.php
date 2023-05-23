<?php

use App\Http\Controllers\CustomerController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("admin/addpackage",[HostingPlanController::class,'store']);
Route::delete("admin/deletepackage/{id}",[HostingPlanController::class,'destroy']);
Route::post("admin/updatepackage/{id}",[HostingPlanController::class,'update']);
Route::get("showpackages",[HostingPlanController::class,'index']);
Route::get("admin/showcustomers",[CustomerController::class,'index']);
Route::delete("admin/deletecustomer/{id}",[CustomerController::class,'destroy']);
Route::post("orderpackage",[OrderController::class,'store']);
Route::get("showorders/{id}",[OrderController::class,'index']);
Route::get("showorders",[OrderController::class,'index']);


