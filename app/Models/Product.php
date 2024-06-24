<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'category_id', 'description', 'price'];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
    }
    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_products')->withPivot('quantity', 'price');
    }

    public  function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
