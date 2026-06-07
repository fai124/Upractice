<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Favorite::where('user_id', Auth::id())->with('recipe')->get();
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
    public function store(Recipe $recipe)
    {
        $fave = Favorite::where('recipe_id', $recipe->id)->where('user_id', Auth::id())->first();
        if ($fave) {
            $fave->delete();
        } else {
            $fave = new Favorite();
            $fave->recipe_id = $recipe->id;
            $fave->user_id = Auth::id();
            $fave->save();
        }
        $faves = Favorite::where('user_id', Auth::id())->pluck('recipe_id')->toArray();

        return response()->json($faves);
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavoriteRequest $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        $favorite->delete();
        return response()->json(['favorite' => 'Рецепт удалён из избранного']);
    }
}
