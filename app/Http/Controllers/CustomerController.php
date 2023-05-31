<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function addCompany(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required'
        ]);

        $user = auth()->user();

        Customer::create(['user_id' => $user->id , 'company_name' => $validated['company_name']]);
        $user->role = 'customer' ;
        $user->save();

        return response()->json('company added successfully');
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ["customer" => customer::all()];
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
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, customer $customer)
    {
        $user = User::where('id', $id)->whereHas('customer', function ($query) {
            $query->whereNotNull('company_name');
        })->first();
// Update the role attribute for user
            $user->role = 'customer';
            $user->save();
            return response()->json('role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(customer $customer,$id)
    {
        $customer = customer::find($id);
        if($customer->delete())
       {
        return ["status"=>"Done"];
       }
       else { return ["status"=>"Failed"];}
    }
}
