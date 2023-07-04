<?php

namespace App\Http\Controllers;

use App\Models\hostingPlan;
use Illuminate\Http\Request;

class HostingPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = hostingPlan::all()->where('available','1');

        return response()->json(['packages' => $packages],200);
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
        try{
        $hostingPlan = new hostingPlan();
        $hostingPlan->package_type=$request->package_type;
        $hostingPlan->available=$request->available;
        $hostingPlan->space=$request->space;
        $hostingPlan->bandwidth=$request->bandwidth;
        $hostingPlan->email_accounts=$request->email_accounts;
        $hostingPlan->mysql_accounts=$request->mysql_accounts;
        $hostingPlan->php_enabled=$request->php_enabled;
        $hostingPlan->ssl_certificate=$request->ssl_certificate;
        $hostingPlan->duration=$request->duration;
        $hostingPlan->yearly_price=$request->yearly_price;
        $hostingPlan->yearly_price_outside_syria=$request->yearly_price_outside_syria;
       if($hostingPlan->save())
       {
        return response()->json(['status' => 'success',
                'message' => 'Hosting plan added successfully']);
       }
       else {
        return response()->json(['status' => 'failed',
                                 'message' => 'Adding hosting plan failed']);
    }
    }catch(Exception $e){
        return response()->json(['status' => 'failed',
                                 'message' => 'Can`t connect to database']);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(hostingPlan $hostingPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hostingPlan $hostingPlan)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, hostingPlan $hostingPlan, $id)
    {

        $hostingPlan = hostingPlan::find($id);
        $hostingPlan->package_type=$request->package_type;
        $hostingPlan->available=$request->available;
        $hostingPlan->space=$request->space;
        $hostingPlan->bandwidth=$request->bandwidth;
        $hostingPlan->email_accounts=$request->email_accounts;
        $hostingPlan->mysql_accounts=$request->mysql_accounts;
        $hostingPlan->php_enabled=$request->php_enabled;
        $hostingPlan->ssl_certificate=$request->ssl_certificate;
        $hostingPlan->duration=$request->duration;
        $hostingPlan->yearly_price=$request->yearly_price;
        $hostingPlan->yearly_price_outside_syria=$request->yearly_price_outside_syria;
        if($hostingPlan->save())
       {
        return response()->json(['status' => 'success',
                                 'message' => 'Hosting plan updated successfully']);
       }
       else { return response()->json(['status' => 'failed',
                                        'message' => 'Updating hosting plan failed']);}

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(hostingPlan $hostingPlan,$id)
    {
        $hostingPlan = hostingPlan::find($id);
        if($hostingPlan->delete())
       {
        return response()->json(['status' => 'success',
                                 'message' => 'Hosting plan deleted successfully']);
       }
       else { return response()->json(['status' => 'failed',
                                       'message' => 'Hosting plan deletion failed']);}

    }

    public function renewHostingPlan()
    {
        $customerId = customer::where('user_id', auth()->id())->first()->id;
        $hostingPlan = customerHostingPlan::where('customer_id', $customerId)->latest()->first();
        $hostingPlanExpiryDate = $hostingPlan->expiry_date ;
        $hostingPlan->update(['expiry_date' => Carbon::parse($hostingPlanExpiryDate)->addYear()]);
    }
    public function upgradeHostingPlan(hostingPlan $hostingPlan)
    {
        $customerId = customer::where('user_id', auth()->id())->first()->id;
        $customerHostingPlan = customerHostingPlan::where('customer_id', $customerId)->latest()->first();
        $hostingPlanExpiryDate = $customerHostingPlan->expiry_date ;
        $customerHostingPlan->update(['expiry_date' => Carbon::parse($hostingPlanExpiryDate)->addYear(),
                                      'hostingplan_id' => $hostingPlan->id,
                                      'price' => $hostingPlan->yearly_price]);
        $customerHostingPlan->save();
    }
}
