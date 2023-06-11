<?php

namespace App\Http\Controllers;

use App\Models\notification;
use App\Models\customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $package_expire_noti = collect([]);
        $ticket_message_noti = collect([]);

        $checkIfCustomer = customer::where('user_id',Auth::id())->first();
        if($checkIfCustomer != null){

        $package_expire = notification::where('notification_type','package_expiration')
                                        ->where('customer_id',$checkIfCustomer->id)
                                        ->where('seen_by_customer', null)->get();

        $ticket_message = notification::where('notification_type','ticket_message')
                                        ->where('customer_id',$checkIfCustomer->id)
                                        ->where('receiver','Customer')
                                        ->where('seen_by_customer', null)->get();
        foreach($package_expire as $p_e){
            $package_expire_noti->push([
                'notification' => $p_e->id,
                'notification_type' => $p_e->notification_type,
                'content' => $p_e->content,
                'created_at' => $p_e->created_at
            ]);
        }
        foreach($ticket_message as $t_m){
            $ticket_message_noti->push([
                'notification' => $t_m->id,
                'notification_type' => $t_m->notification_type,
                'content' => $t_m->content,
                'created_at' => $t_m->created_at
            ]);
        }
         return response()->json(['package_expire_notifications' => $package_expire_noti,
                                 'ticket_message_notification' => $ticket_message_noti]);
    }else{
        $ticket_message = notification::where('notification_type','ticket_message')
                                        ->where('user_id',Auth::id())
                                        ->where('receiver','User')
                                        ->where('seen_by_user', null)->get();
        foreach($ticket_message as $t_m){
            $ticket_message_noti->push([
                'notification' => $t_m->id,
                'notification_type' => $t_m->notification_type,
                'content' => $t_m->content,
                'created_at' => $t_m->created_at
            ]);
        }
    return response()->json(['ticket_message_notification' => $ticket_message_noti]);

    }
    }

    public function getAllNotifications()
    {
         $package_expire_noti = collect([]);
        $ticket_message_noti = collect([]);

        $checkIfCustomer = customer::where('user_id',Auth::id())->first();
        if($checkIfCustomer != null){

        $package_expire = notification::where('notification_type','package_expiration')
                                        ->where('customer_id',$checkIfCustomer->id)->get();

        $ticket_message = notification::where('notification_type','ticket_message')
                                        ->where('customer_id',$checkIfCustomer->id)
                                        ->where('receiver','Customer')->get();
        foreach($package_expire as $p_e){
            $package_expire_noti->push([
                'notification' => $p_e->id,
                'notification_type' => $p_e->notification_type,
                'content' => $p_e->content,
                'created_at' => $p_e->created_at
            ]);
        }
        foreach($ticket_message as $t_m){
            $ticket_message_noti->push([
                'notification' => $t_m->id,
                'notification_type' => $t_m->notification_type,
                'content' => $t_m->content,
                'created_at' => $t_m->created_at
            ]);
        }
         return response()->json(['package_expire_notifications' => $package_expire_noti,
                                 'ticket_message_notification' => $ticket_message_noti]);
    }else{
        $ticket_message = notification::where('notification_type','ticket_message')
                                        ->where('user_id',Auth::id())
                                        ->where('receiver','User')->get();
        foreach($ticket_message as $t_m){
            $ticket_message_noti->push([
                'notification' => $t_m->id,
                'notification_type' => $t_m->notification_type,
                'content' => $t_m->content,
                'created_at' => $t_m->created_at
            ]);
        }
    return response()->json(['ticket_message_notification' => $ticket_message_noti]);

    }
}

    public function seenNotification($id)
    {
        $checkIfCustomer = customer::where('user_id',Auth::id())->first();
        if($checkIfCustomer != null){
            $noti = notification::find($id);
            if($noti != null){
                $noti->update(['seen_by_customer' => Carbon::now()->format('Y-m-d H:i:s')]);
                return response()->json(['status' => 'success',
                                        'message' => 'notification updated successfully']);
            }else{
                return response()->json(['status' => 'failed',
                                        'message' => 'notification update failed due to unknown `id` ']);

            }
        }else{
            $noti = notification::find($id);
            if($noti != null){
                $noti->update(['seen_by_user' => Carbon::now()->format('Y-m-d H:i:s')]);
                return response()->json(['status' => 'success',
                                        'message' => 'notification updated successfully']);
            }else{
                return response()->json(['status' => 'failed',
                                        'message' => 'notification update failed due to unknown `id` ']);

            }
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(notification $notification)
    {
        //
    }
}
