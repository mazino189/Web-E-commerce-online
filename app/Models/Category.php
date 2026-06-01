<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    // this is fillable fields for category model //
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];
    // relation with product model //
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
