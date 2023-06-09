<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\supportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getMyTickets()
    {
        $customerId = Customer::where('user_id', auth()->id())->first()->id;
        $tickets = supportTicket::where('customer_id', $customerId)->get();
        return response()->json($tickets);
    }
    public function openTicket()
    {
        $customerId = Customer::where('user_id', auth()->id())->first()->id;
        // Create a new support ticket
        $ticket = supportTicket::create([
            'customer_id' => $customerId,
            'open_time' => now(),
            'status' => 'Open',
        ]);
        return response()->json($ticket);
    }

    public function closeTicket($ticketId)
    {
        $ticket = supportTicket::findOrFail($ticketId);
        $ticket->status = 'closed';
        $ticket->close_time = now();
        $ticket->save();

        return response()->json($ticket);
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
    public function show(supportTicket $supportTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(supportTicket $supportTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, supportTicket $supportTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(supportTicket $supportTicket)
    {
        //
    }
}
