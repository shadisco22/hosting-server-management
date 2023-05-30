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
        return ["packages" => hostingPlan::all()];
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
        return ["status"=>"Done"];
       }
       else {
        return ["status"=>"Failed"];
    }
    }catch(Exception $e){
        return ["status"=>"Failed"];
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
        return ["status"=>"Done"];
       }
       else { return ["status"=>"Failed"];}

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(hostingPlan $hostingPlan,$id)
    {
        $hostingPlan = hostingPlan::find($id);
        if($hostingPlan->delete())
       {
        return ["status"=>"Done"];
       }
       else { return ["status"=>"Failed"];}

    }
}
