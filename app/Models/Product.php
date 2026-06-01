<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;

class Product extends Model
{
    // this is fillable fields for product model //
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'category_id',
        'brand_id',
    ];  
    // relation product with category model //
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
