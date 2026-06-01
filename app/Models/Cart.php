<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Cart extends Model
{
    // this is fillable fields for cart model //
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'status',
    ];
    // relation with user model //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
