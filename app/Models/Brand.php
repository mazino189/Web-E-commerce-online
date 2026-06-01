<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Brand extends Model
{
    // this is fillable fields for brand model //
    // fillable fields
    protected $fillable =[
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
