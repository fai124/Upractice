<?php

namespace App\Http\Controllers;

use App\Models\RecipeStep;
use App\Http\Requests\StoreRecipeStepRequest;
use App\Http\Requests\UpdateRecipeStepRequest;
use Illuminate\Support\Facades\Storage;

class RecipeStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSteps($recipe_id)
    {
        return RecipeStep::get();
    }

    /**
     * Show the form for creating a new resource.
     */
     public function saveStep(StoreRecipeStepRequest $request, $recipe_id)
    {
        if ($request->id) {
            $step = RecipeStep::find($request->id);
        } else {
            $step = new RecipeStep();
        }
        $step->description = $request->description;
        if ($request->file) {
            $step->image_url = Storage::disk("public")->putFile('images', $request->file('file'));
        }
        $step->step_number = $request->step_number;
        $step->recipe_id = $recipe_id;
        $step->save();
        return ['saved' => ['step' =>'Добавился/Изменился шаг'], $request->all()];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function delStep(RecipeStep $id)
    {
        $id->delete();
        return response()->json(['message' => 'Шаг удалён']);
    }

    /**
     * Display the specified resource.
     */
    public function show(RecipeStep $recipeStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecipeStep $recipeStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeStepRequest $request, RecipeStep $recipeStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecipeStep $recipeStep)
    {
        //
    }
}
