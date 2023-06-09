<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\User;
use App\Models\hostingPlan;
use App\Models\notification;
use App\Models\customerHostingPlan;
use App\Models\activities_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
    public function customerInfo()
    {
        //Auth::id()
        $customer_id = 1;
        $customer = customer::find($customer_id);
        if($customer != null){
            $user = User::find($customer->user_id);
            if($user != null){
                $cus_hostingplans = customer::find($customer_id)->customerHostingPlan()->get();
                $days_left = -1;
                foreach($cus_hostingplans as $cus_hostingplan){
                    $expiry_date = new  Carbon($cus_hostingplan->expiry_date);
                    $today_date = Carbon::now()->format('y-m-d');
                    $days_left = $expiry_date->diff($today_date)->days;

                    $notified_before = notification::where('customer_id',1)
                                                ->where('customer_hosting_plan_id',$cus_hostingplan->hostingplan_id)
                                                ->where('notification_type','package_expiration')->get();

                    if($days_left <= 10 && empty($notified_before))
                    {
                        $customer_package = hostingPlan::find($cus_hostingplan->hostingplan_id);
                        $package_name = $customer_package->package_type;
                        notification::create([
                            'customer_id' => 1,
                            'customer_hosting_plan_id' => $cus_hostingplan->hostingplan_id,
                            'notification_type' => 'package_expiration',
                            'content' => 'Your subscirption in package : '.$package_name." will expire in : ".$days_left." please renew this package if you wish to use it "
                        ]);
                    }
                }

                    $customer_info = [
                        'id' => 1,
                        'f_name' => $user->f_name,
                        'l_name' => $user->l_name,
                        'address' => $user->address,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'company_name' => $customer->company_name,
                        'packages'=> $cus_hostingplans
                    ];



                    return response()->json($customer_info);
            }
            else{
                return response()->json(['status' => 'failed',
                                         'message' => 'Customer has no record in `user` table']);
            }
        }
        else{
                return response()->json(['status' => 'failed',
                                         'message' => 'Customer not found']);
        }
    }
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
                return response()->json(['status' =>'success',
                                         'message' => 'Customer updated successfully']);
            } else {
                return response()->json(['status' => 'failed',
                                         'message' => 'Customer has no record in `user` table']);
            }
        } else {
            return response()->json(['status' => 'failed',
                                     'message' => 'Customer does not exist']);
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
        $user_id = $customer->user_id;
        $user = User::find($user_id);
        if ($customer->delete()) {
            if($user->delete()){
                activities_log::create([
                    'user_id' => 4,
                    'activity_type' => 'delete',
                    'on_table' => 'customers',
                    'record_id' => $id
                ]);
                return response()->json(['status' => 'success',
                                        'message' => 'Customer deleted successfully']);
         }
        } else {
            return response()->json(['status' => 'failed',
                                     'message' => 'Customer deletion failed']);
        }
    }
}
