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
Route::get('/SingleRecipe/{recipe}', [RecipeController::class, 'getRecipe']);
Route::get('/SingleRecipeAuth/{recipe}', [RecipeController::class, 'getRecipe']);
Route::get('/recipes/{recipe}', [RecipeController::class, 'show']);
Route::get('/recipe/{recipe}', [RecipeController::class, 'show']);

Route::get('/Ingredients', [IngredientController::class, 'getIngredients']);
Route::post('/Ingredients', [IngredientController::class, 'save']);
Route::delete('/Ingredients/{ingredient}', [IngredientController::class, 'removeIngredient']);

Route::get('/getSteps/{recipe_id}', [RecipeStepController::class, 'getSteps']);
Route::post('/saveStep/{recipe_id}', [RecipeStepController::class, 'saveStep']);
Route::delete('/delStep/{id}', [RecipeStepController::class, 'removeStep']);

Route::get('/ingredients/{recipe_id}', [IngredientController::class, 'index']);
Route::get('/ingRecipe/{recipe_id}', [RecipeIngredientController::class, 'index']);
Route::post('/ingSave/{recipe_id}', [RecipeIngredientController::class, 'saveIngredients']);
Route::delete('/ingRem/{rec}', [RecipeIngredientController::class, 'removeIngredient']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::post('/EditCategory/{category}', [CategoryController::class, 'update']);
Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

Route::get('/Fave', [FavoriteController::class, 'index']);
Route::post('/FaveRec/{recipe_id}', [FavoriteController::class, 'toggleFavorite']);
Route::delete('/DelFave/{favorite}', [FavoriteController::class, 'removeFavorite']);

Route::post('/changeStep/{recipe}', [UserStepController::class, 'saveStep']);

Route::post('/addRecipe', [RecipeController::class, 'store']);
Route::post('/EditRecipe/{recipe}', [RecipeController::class, 'update']);
Route::delete('/DeleteRecipe/{recipe}', [RecipeController::class, 'destroy']);

Route::post('/recipes/{recipe}/favorite', [RecipeController::class, 'favorite']);
Route::delete('/recipes/{recipe}/favorite', [RecipeController::class, 'delFave']);

Route::get('/UsersControl', [UserController::class, 'UsersControl']);
Route::post('/logout', [UserController::class, 'logout']);
