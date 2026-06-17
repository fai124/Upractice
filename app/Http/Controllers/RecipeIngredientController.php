<?php

namespace App\Http\Controllers;

use App\Models\RecipeIngredient;
use App\Http\Requests\StoreRecipeIngredientRequest;
use App\Http\Requests\UpdateRecipeIngredientRequest;
use App\Models\Ingredient;
use App\Models\Recipe;

class RecipeIngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($recipe_id)
    {
        return RecipeIngredient::where('recipe_id', $recipe_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function saveIngredients($recipe_id, StoreRecipeIngredientRequest $request)
    {

        foreach (json_decode($request->ingredients) as $ingr) {
            if (isset($ingr->id)) {
                $ing = RecipeIngredient::where("id", $ingr->id)->first();
            } else {
                $ing = new RecipeIngredient();
            }
            $ing->recipe_id = $recipe_id;
            $ing->ingredient_id = $ingr->ingredient_id;
            $ing->quantity = $ingr->quantity;
            $ing->save();
        }
        return response()->json(['saved' => ['ingredient' => 'Ингредиент добавлен/изменён']]);
    }
    public function removeIngredient(RecipeIngredient $rec)
    {
        $rec->delete();
        return response()->json(['deleted' => 'Ингредиент удалён']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeIngredientRequest $request)
    {
        $ing = new RecipeIngredient();
        return response()->json(['added' => 'Добавлен ингредиент']);
    }

    /**
     * Display the specified resource.
     */
    public function show(RecipeIngredient $recipeIngredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecipeIngredient $recipeIngredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeIngredientRequest $request, RecipeIngredient $recipeIngredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecipeIngredient $recipeIngredient)
    {
        //
    }
}
