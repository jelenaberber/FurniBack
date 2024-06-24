<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'orders_products';
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
}
