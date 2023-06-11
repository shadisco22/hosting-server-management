<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\message;
use App\Models\supportTicket;
use App\Models\notification;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getTicketMessages($id)
    {
        $messages = message::where('support_ticket_id', $id)->orderBy('created_at')->get();

        $checkCustomer = customer::where('user_id' , $id)->first();
        if ($checkCustomer)
            $senderId = $checkCustomer->id;
        else
            $senderId = auth()->id();
        foreach ($messages as $message)
        {
            if($message->sender == $senderId)
                $message->is_mine = true;
            else
                $message->is_mine = false;
        }
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $id)
    {
        $validatedData = $request->validate(
            [
                'message' => 'required'
            ]
        );

        $senderId = auth()->id();

        $checkCustomer = customer::where('user_id' , auth()->id())->first();

        if ($checkCustomer)
            $senderId = $checkCustomer->id;


        $message = message::create(
            [
                'support_ticket_id' => $id,
                'sender' => $senderId,
                'message' => $validatedData['message']
            ]
        );


        // generating receiver notification
        $st = supportTicket::find($id);
        if($st->user_id != null){
            $receiver = 'Customer';
            if($checkCustomer != null){
                $receiver = 'User';
            }
            notification::create([
                'customer_id' => $st->customer_id,
                'user_id' => $st->user_id,
                'notification_type' => 'ticket_message',
                'receiver' => $receiver,
                'content' => 'You have a new message ',
            ]);
        }
        return response()->json($message);
    }
}
