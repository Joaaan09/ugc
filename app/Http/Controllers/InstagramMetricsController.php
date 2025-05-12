<?php

namespace App\Http\Controllers;

use App\Models\InstagramMetrics;
use App\Http\Requests\StoreInstagramMetricsRequest;
use App\Http\Requests\UpdateInstagramMetricsRequest;
use App\Models\Influencer;
use App\Models\SocialProfile;
use App\Models\Platform;

class InstagramMetricsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all Instagram metrics
        
        $influencersIdsbyInstagram = SocialProfile::select('influencer_id')->where('platform_id', Platform::where('name','Instagram')->first()->id)->get();
        $influencers = Influencer::whereIn('id',$influencersIdsbyInstagram)->get();
        // Return the view with the Instagram metrics data
        return view('instagram_metrics.index', compact('influencers'));
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
    public function store(StoreInstagramMetricsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Fetch the Instagram metrics for the specified influencer
        $instagramMetrics = InstagramMetrics::where('social_profile_id', $id)->get();

        // Return the view with the Instagram metrics data
        return view('instagram_metrics.show', compact('instagramMetrics'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstagramMetrics $instagramMetrics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstagramMetricsRequest $request, InstagramMetrics $instagramMetrics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstagramMetrics $instagramMetrics)
    {
        //
    }
}
