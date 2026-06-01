<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;
class OrderItem extends Model
{
    // this is fillable fields for order item model //
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];
    // relation order with order item model //
    public function order()
    {        
        return $this->belongsTo(Order::class);
    }
    // relation product with order item model //
    public function product()
    {
        return $this->belongsTo(Product::class);
    }    
}
