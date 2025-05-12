<?php

namespace App\Http\Controllers;

use App\Models\InstagramPost;
use App\Http\Requests\StoreInstagramPostRequest;
use App\Http\Requests\UpdateInstagramPostRequest;

class InstagramPostController extends Controller
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
    public function store(StoreInstagramPostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InstagramPost $instagramPost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstagramPost $instagramPost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstagramPostRequest $request, InstagramPost $instagramPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstagramPost $instagramPost)
    {
        //
    }
}
