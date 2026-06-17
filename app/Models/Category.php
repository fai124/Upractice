<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
    protected $fillable = [
        'name',
    ];
}
