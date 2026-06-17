<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getIngredients()
    {
        return Ingredient::get()->keyBy('id');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function save(StoreIngredientRequest $request)
    {
    if ($request->id) {
        $ingredient = Ingredient::find($request->id);
        if (!$ingredient) {
            return response()->json(['error' => 'Ингредиент не найден'], 404);
        }
    } else {
        $ingredient = new Ingredient();
    }

    $ingredient->name = $request->name;
    $ingredient->unit = $request->unit;
    $ingredient->save();

    return response()->json($ingredient, $request->id ? 200 : 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIngredientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient)
    {
        $ingredient->name = $request->name;
        $ingredient->unit = $request->unit;
        $ingredient->save();

    return response()->json($ingredient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function removeIngredient(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json(['message' => 'удалён']);
    }
}
