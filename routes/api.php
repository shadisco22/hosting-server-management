<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ActivitiesLogController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HostingPlanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\MessageController;

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

    Route::post('customer/confirmpassword',[CustomerController::class,'confirmPassword']);
    Route::get('customer/info', [CustomerController::class, 'customerInfo']);
    Route::get('customer/get_notifications', [NotificationController::class, 'getNotifications']);
    Route::get('customer/get_all_notifications', [NotificationController::class, 'getAllNotifications']);
    Route::get('customer/seen_notifications/{id}', [NotificationController::class, 'seenNotification']);

    Route::get('customer/get-my-tickets',[SupportTicketController::class,'getMyTickets']);
    Route::get('customer/open-ticket',[SupportTicketController::class,'openTicket']);
    Route::post('customer/close-ticket/{id}',[SupportTicketController::class,'closeTicket']);
    Route::get('customer/get-ticket-messages/{id}',[MessageController::class,'getTicketMessages']);
    Route::post('customer/send-message/{id}',[\App\Http\Controllers\MessageController::class, 'sendMessage']);


});
Route::post('customer/pay', [OrderController::class, 'pay'])->name('pay');
Route::get('customer/success', [OrderController::class, 'success']);
Route::get('customer/error', [OrderController::class, 'error']);
Route::get('customer/showpackages', [HostingPlanController::class, 'index']);
Route::post('customer/orderpackage', [OrderController::class, 'store']);
Route::put('customer/editprofile/{id}', [CustomerController::class, 'update']);
Route::post('customer/alharam', [OrderController::class, 'store']);


// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'checkRole:Admin']], function () {
        Route::get('admin/info', [Admin::class, 'adminInfo']);
        Route::get('admin/get_notifications', [NotificationController::class, 'getNotifications']);
        Route::get('admin/get_all_notifications', [NotificationController::class, 'getAllNotifications']);
        Route::get('admin/seen_notifications/{id}', [NotificationController::class, 'seenNotification']);
        Route::get('admin/renew',[HostingPlanController::class,'renewHostingPlan']);
        Route::get('admin/upgrade/{hostingPlan}',[HostingPlanController::class,'upgradeHostingPlan']);
});
Route::post('admin/createoperator', [Admin::class, 'createOperator']);
Route::put('admin/updateoperator/{id}',[Admin::class , 'update']);
Route::delete("admin/deleteoperator/{id}", [Admin::class, 'destroy']);
Route::get('admin/showusers', [Admin::class, 'show']);

Route::post("admin/addpackage", [HostingPlanController::class, 'store']);
Route::delete("admin/deletepackage/{id}", [HostingPlanController::class, 'destroy']);
Route::put("admin/updatepackage/{id}", [HostingPlanController::class, 'update']);
Route::get("admin/showpackages", [HostingPlanController::class, 'index']);
Route::delete("admin/deletecustomer/{id}", [CustomerController::class, 'destroy']);
Route::get("admin/showorders", [OrderController::class, 'index']);
Route::get("admin/activitieslog",[ActivitiesLogController::class, 'index']);

// Operator routes
Route::group(['middleware' => ['auth:sanctum', 'checkRole:Operator']], function () {

    Route::get('operator/approved/{order}',[\App\Http\Controllers\OrderController::class,'approve']);
    Route::get('operator/disapproved/{order}',[\App\Http\Controllers\OrderController::class,'disapprove']);
    Route::get('operator/info', [Admin::class, 'operatorInfo']);
    Route::get('operator/get_notifications', [NotificationController::class, 'getNotifications']);
    Route::get('operator/get_all_notifications', [NotificationController::class, 'getAllNotifications']);
    Route::get('operator/seen_notifications/{id}', [NotificationController::class, 'seenNotification']);
});
Route::get("operator/showorders", [OrderController::class, 'index']);
