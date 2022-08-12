<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BigcommerceStoreUser extends Pivot
{
    use HasFactory;

    public $table = 'bigcommerce_store_users';

    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_owner', 'user_id', 'bigcommerce_store_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(BigcommerceStore::class, 'bigcommerce_store_id', 'id');
    }

    /**
     * Scope a query to only include owner users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwner($query)
    {
        return $query->where('is_owner', true);
    }

}
