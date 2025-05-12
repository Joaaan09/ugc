<?php

namespace App\Http\Controllers;

use App\Models\SocialProfile;
use App\Http\Requests\StoreSocialProfileRequest;
use App\Http\Requests\UpdateSocialProfileRequest;
use App\Models\Platform;

class SocialProfileController extends Controller
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
    public function store(StoreSocialProfileRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialProfile $socialProfile)
    {
        //Si el perfil social es de tipo Instagram, redirigir a la vista de Instagram
        if($socialProfile->platform == Platform::where('name','Instagram')->first()){
            return view('instagram.show',compact('socialProfile'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialProfile $socialProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocialProfileRequest $request, SocialProfile $socialProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialProfile $socialProfile)
    {
        //
    }
}
