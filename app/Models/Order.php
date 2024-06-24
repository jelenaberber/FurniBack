<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'city', 'address',
        'zip_code', 'phone', 'final_price', 'delivery'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($order) {
            if ($order->isForceDeleting()) {
                $order->orderProducts()->forceDelete();
            } else {
                $order->orderProducts()->delete();
            }
        });
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }
}
