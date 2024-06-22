<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements  JWTSubject
{
    use HasFactory;
    use SoftDeletes;

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'profile_img',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            "id"=>$this->id,
            "username"=>$this->username,
            "email"=>$this->email
        ];
    }
}
