<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CustomerController extends Controller
{

    // public function addCompany(Request $request)
    // {
    //     $validated = $request->validate([
    //         'company_name' => 'required'
    //     ]);

    //     $user = auth()->user();

    //     Customer::create(['user_id' => $user->id, 'company_name' => $validated['company_name']]);
    //     $user->role = 'customer';
    //     $user->save();

    //     return response()->json('company added successfully');
    // }



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
        // store customer, user -> customer
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
    public function update(Request $request, customer $customer, $id)
    {
        // Update customer profile

        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'company_name' => 'required|string|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $customer = customer::find($id);
        if ($customer != null) {
            $user = User::find($customer->user_id);
            if ($user != null) {
                User::whereId($user->id)->update([
                    'f_name' => $request->f_name,
                    'l_name' => $request->l_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                customer::whereId($customer->id)->update([
                    'company_name' => $request->company_name
                ]);
                return response()->json(["status" => "Updated successfully"]);
            } else {
                return response()->json(["status" => "Customer has no record in 'user' table"]);
            }
        } else {
            return response()->json(["status" => "Customer does not exist"]);
        }





        // $user = User::where('id', $id)->whereHas('customer', function ($query) {
        //     $query->whereNotNull('company_name');
        // })->first();
        // // Update the role attribute for user
        // $user->role = 'customer';
        // $user->save();
        // return response()->json('role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(customer $customer, $id)
    {
        $customer = customer::find($id);
        if ($customer->delete()) {
            return ["status" => "Done"];
        } else {
            return ["status" => "Failed"];
        }
    }
}
