<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeStepRequest;
use App\Models\Recipe;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Ingredient;
use App\Models\RecipeStep;
use App\Models\User;
use App\Models\UserStep;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::get();
        if ($request->sort) {
            $sort = explode('|', $request->sort);
        } else {
            $sort = ['cook_time', 'asc'];
        }

        $recipes = Recipe::where(function ($query) use ($request) {
            if ($request->search) {
                $query->whereLike('title', '%' . $request->search . '%');
            }
            $cat = json_decode($request->cat_filter);
            if ($cat) {
                if (count($cat) > 0) {
                    $query->whereIn('category_id', $cat);
                }
            }

            if ($request->cook_time) {
                switch ($request->cook_time) {
                    case 0:
                        $cook_time = [];
                        break;
                    case 1:
                        $cook_time = [0, 30];
                        break;
                    case 2:
                        $cook_time = [30, 60];
                        break;
                    case 3:
                        $cook_time = [60, 3000];
                        break;
                }
                $query->whereBetween('cook_time', $cook_time);
            }
        })->orderBy($sort[0], $sort[1])->with('category')->get();
        $faves = [];
        if (isset($request->header()['userid'])) {
            $faves = Favorite::where('user_id', $request->header()['userid'])->pluck('recipe_id')->toArray();
        }

        return ['recipes' => $recipes, 'faves' => $faves, 'categories' => $categories];
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
    public function store(StoreRecipeRequest $request)
    {
        $recipe = new Recipe();
        $recipe->category_id = $request->category_id;
        $recipe->title = $request->title;
        $recipe->description = $request->description;
        $recipe->cook_time = $request->cook_time;
        $recipe->difficulty = $request->difficulty;
        if ($request->image_url) {
            $recipe->image_url = Storage::disk("public")->putFile('images', $request->file('image_url'));
        }
        $recipe->save();
        return response()->json(['Recipe' => 'Рецепт создан']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        return $recipe;
    }

    public function getRecipe($recipe, Request $request)
    {
        $recipe = Recipe::with('category', 'steps', 'ingredients')->findOrFail($recipe);
        $categories = Category::get();
        $step = 0;
        $faves = [];
        if (Auth::user()) {
            $us = UserStep::where('user_id', Auth::id())->where('recipe_id', $recipe->id)->first();
            if ($us) {
                $step = $us->step_number;
            }
            if (isset($request->header()['userid'])) {
                $faves = Favorite::where('user_id', $request->header()['userid'])->pluck('recipe_id')->toArray();
            }
        }
        $ingredients = Ingredient::get()->keyBy('id');
        return ['recipe' => $recipe, 'step' => $step, 'ingredients' => $ingredients, 'categories' => $categories, 'faves' => $faves];
    }

    /**
     * Show the form for editing the specified resource.
     */
   
    
    

    /**
     * Update the specified resource in storage.
     */
    public function save(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $recipe->category_id = $request->category_id;
        $recipe->title = $request->title;
        $recipe->description = $request->description;
        $recipe->cook_time = $request->cook_time;
        $recipe->difficulty = $request->difficulty;
        if ($request->image_url) {
            $recipe->image_url = Storage::disk("public")->putFile('images', $request->file('image_url'));
        }
        $recipe->save();
        return response()->json(['Recipe' => 'Рецепт редактирован']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return response()->json(['Recipe' => 'Рецепт удалён']);
    }
    public function favorite(Recipe $recipe)
    {
        //
    }
    public function delFave(Recipe $recipe)
    {
        //
    }
}
