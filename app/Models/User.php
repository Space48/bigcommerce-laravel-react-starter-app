<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bigcommerce_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Retrieve BigcommerceStores that this user has access to
     *
     */
    public function stores()
    {
        return $this->hasManyThrough(
            BigcommerceStore::class,
            BigcommerceStoreUser::class,
            'user_id',
            'id',
            'id',
            'bigcommerce_store_id'
        );
    }

    /**
     * Scope a query to find user by email
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithEmail(Builder $query, string $email)
    {
        return $query->where('email', $email);
    }
}
