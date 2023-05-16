<?php

namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id=-1)
    {
        if($id == -1)
        return order::all();

        else if(order::find($id))
            return order::find($id);

        else return ['status' => 'order not found'];
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
        $order =new order();
        $order->customer_id =$request->customer_id;
        $order->hostingplan_id =$request->hostingplan_id;
        $order->receipt_path =$request->receipt_path;
        $order->status =$request->status;
        $order->final_price =$request->final_price;
        $order->save();

        return ["status"=>"Done"];
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        //
    }
}
