<?php

namespace App\Http\Controllers;

use App\Models\activities_log;
use Illuminate\Http\Request;

class ActivitiesLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['logs' => activities_log::all()]);
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
    public function show(activities_log $activities_log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(activities_log $activities_log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, activities_log $activities_log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(activities_log $activities_log)
    {
        //
    }
}
