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
use Illuminate\Support\Facades\Auth;

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


    public function confirmPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed',
                                     'message' => $validator->errors()]);
        }
        if(Hash::check($request->password,Auth::user()->password)){
            return response()->json(['status' => 'success',
                                     'message' => 'Password is correct']);
        }else{
            return response()->json(['status' => 'failed',
                                     'message' => 'Incorrect password']);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function customerInfo()
    {
        //Auth::id()
        $customer = customer::where('user_id',Auth::id())->first();
        $customer_id = $customer->id;
        if($customer != null){
            $user = User::find($customer->user_id);
            if($user != null){
                $packages = collect([]);
                $cus_hostingplans = customer::find($customer_id)->customerHostingPlan()->get();
                $days_left = -1;
                $notified_before = [];

                foreach($cus_hostingplans as $cus_hostingplan){

                    $expiry_date = new  Carbon($cus_hostingplan->expiry_date);
                    $today_date = Carbon::now()->format('y-m-d');
                    if($expiry_date->lt($today_date)){customerHostingPlan::find($cus_hostingplan->id)->update(['status' => 'Inactive']);}
                    $days_left = $expiry_date->diff($today_date)->days;
                    if($cus_hostingplan->status == 'Inactive'){$days_left = $days_left * -1;}
                    else{

                    $notified_before = notification::where('customer_id', $customer_id)
                                                    ->where('customer_hosting_plan_id',$cus_hostingplan->id)
                                                    ->where('notification_type','package_expiration')->first();


                    if($days_left <= 10 && empty($notified_before))
                    {
                        $customer_package = hostingPlan::find($cus_hostingplan->hostingplan_id);
                        $package_name = $customer_package->package_type;
                        notification::create([
                            'customer_id' => $customer_id,
                            'customer_hosting_plan_id' => $cus_hostingplan->id,
                            'notification_type' => 'package_expiration',
                            'content' => 'Your subscirption in package : '.$package_name." will expire in : ".$days_left." please renew this package if you wish to use it ",
                            'receiver' => 'Customer'
                        ]);
                    }
                }
                    $package = hostingPlan::find($cus_hostingplan->hostingplan_id);
                    $created_at_date = new Carbon($cus_hostingplan->created_at);
                    $created_at_date = $created_at_date->format('y-m-d');
                    $packages->push([
                        'status' => $cus_hostingplan->status,
                        'package_name' => $package->package_type,
                        'package_space' => $package->space,
                        'package_used_space' => '20',
                        'created_at' => '20'.$created_at_date,
                        'expire_at' => $cus_hostingplan->expiry_date,
                        'days_left' => $days_left
                    ]);
            }

                    $customer_info = [
                        'id' => $customer_id,
                        'f_name' => $user->f_name,
                        'l_name' => $user->l_name,
                        'address' => $user->address,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'company_name' => $customer->company_name,
                        'packages'=> $packages
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
            'phone' => 'required|string|max:255',
            'company_name' => 'required|string|min:2'
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
                    'user_id' => Auth::id(),
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
