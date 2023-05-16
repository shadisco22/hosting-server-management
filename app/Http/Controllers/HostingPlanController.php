<?php

namespace App\Http\Controllers;

use App\Models\hostingPlan;
use App\Models\details;
use Illuminate\Http\Request;

class HostingPlanController extends Controller
{
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

        $details =  new details();
        $details->space=$request->space;
        $details->bandwidth=$request->bandwidth;
        $details->email_accounts=$request->email_accounts;
        $details->mysql_accounts=$request->mysql_accounts;
        $details->php_enabled=$request->php_enabled;
        $details->ssl_certificate=$request->ssl_certificate;
        $details->duration=$request->duration;
        $details->yearly_price=$request->yearly_price;
        $details->yearly_price_outside_syria=$request->yearly_price_outside_syria;
        $details->save();

        $details_id = details::orderBy('id','desc')->first()->id;

        $hostingPlan = new hostingPlan();
        $hostingPlan->details_id=$details_id;
        $hostingPlan->package_type=$request->package_type;
        $hostingPlan->available=$request->available;
        $hostingPlan->price=$request->price;
        $hostingPlan->save();

        return ["status"=>"Done"];

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, hostingPlan $hostingPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(hostingPlan $hostingPlan)
    {
        //
    }
}
