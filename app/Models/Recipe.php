<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'cook_time',
        'difficulty',
        'image_url',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function steps()
    {
        return $this->hasMany(RecipeStep::class);
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
