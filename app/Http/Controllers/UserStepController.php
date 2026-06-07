<?php

namespace App\Http\Controllers;

use App\Models\UserStep;
use App\Http\Requests\StoreUserStepRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserStepRequest;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserStepController extends Controller
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
    public function store(Recipe $recipe, Request $request)
    {
        // $us = UserStep::where('user_id', Auth::id())->where('recipe_id', $recipe->id)->first();
        $us = UserStep::firstOrNew(['user_id' => Auth::id(), 'recipe_id' => $recipe->id]);
        $us->step_number = $request->step_number;
        $us->save();
        return response()->json(['step saved']);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserStep $UserStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserStep $UserStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserStepRequest $request, UserStep $UserStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserStep $UserStep)
    {
        //
    }
}
