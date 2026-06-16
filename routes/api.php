<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\RecipeIngredientController;
use App\Http\Controllers\RecipeStepController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/recipes', [RecipeController::class, 'index']);
Route::get('/SingleRecipe/{recipe}', [RecipeController::class, 'SingleRecipe']);
Route::get('/getSteps/{recipe_id}', [RecipeStepController::class, 'getSteps']);
Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);
Route::get('/ingredients/{recipe_id}', [IngredientController::class, 'index']);
Route::get('/ingRecipe/{recipe_id}', [RecipeIngredientController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/SingleRecipeAuth/{recipe}', [RecipeController::class, 'SingleRecipe']);
    Route::get('/EditSingleRecipe/{recipe}', [RecipeController::class, 'SingleRecipe']);
    Route::post('/changeStep/{recipe}', [UserStepController::class, 'store']);
    Route::post('/recipes/{recipe}/favorite', [RecipeController::class, 'favorite']);
    Route::delete('/recipes/{recipe}/favorite', [RecipeController::class, 'delFave']);
    Route::get('/user/favorites', [UserController::class, 'favorites']);
    Route::resource('/recipes', FavoriteController::class)->except('store', 'destroy');
    Route::post('/addRecipe', [RecipeController::class, 'store']);
    Route::post('/EditRecipe/{recipe}', [RecipeController::class, 'update']);
    Route::post('/DeleteRecipe/{recipe}', [RecipeController::class, 'destroy']);
    Route::get('/recipe/{recipe}', [RecipeController::class, 'show']);
    Route::post('/addIng/{recipe_id}', [RecipeIngredientController::class, 'store']);
    Route::post('/ingSave/{recipe_id}', [RecipeIngredientController::class, 'ingSave']);
    Route::delete('/ingRem/{rec}', [RecipeIngredientController::class, 'ingRem']);
    Route::post('/saveStep/{recipe_id}', [RecipeStepController::class, 'saveStep']);
    Route::delete('/delStep/{id}', [RecipeStepController::class, 'delStep']);
    Route::resource('/categories', CategoryController::class)->except('update');
    Route::post('/EditCategory/{category}', [CategoryController::class, 'update']);
    Route::get('/UsersControl', [UserController::class, 'UsersControl']);
    Route::resource('/Fave', FavoriteController::class)->except('store', 'destroy');
    Route::get('/FaveRec/{recipe}', [FavoriteController::class, 'store']);
    Route::get('/DelFave/{favorite}', [FavoriteController::class, 'destroy']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/Ingredients', [IngredientController::class, 'getIngredients']);
    Route::post('/Ingredients', [IngredientController::class, 'saveIngredient']);
    Route::delete('/Ingredients/{ingredient}', [IngredientController::class, 'delIngredient']);
    Route::put('/Ingredients/{ingredient}', [IngredientController::class, 'update']);
});
