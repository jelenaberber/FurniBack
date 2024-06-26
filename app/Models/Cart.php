<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'price', 'numberOfProducts'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_products')->withPivot('quantity', 'price');
    }
}
