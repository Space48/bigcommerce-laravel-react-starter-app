<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class BigcommerceStoreUser
 *
 * @package App\Models
 * @property $id
 * @property User $user
 * @property $user_id
 * @property $bigcommerce_store_id
 * @property $is_owner
 * @property $created_at
 * @property $updated_at
 * @mixin Builder
 * @method static \Database\Factories\BigcommerceStoreUserFactory factory(...$parameters)
 * @method static Builder|BigcommerceStoreUser newModelQuery()
 * @method static Builder|BigcommerceStoreUser newQuery()
 * @method static Builder|BigcommerceStoreUser owner()
 * @method static Builder|BigcommerceStoreUser query()
 * @method static Builder|BigcommerceStoreUser whereBigcommerceStoreId($value)
 * @method static Builder|BigcommerceStoreUser whereCreatedAt($value)
 * @method static Builder|BigcommerceStoreUser whereId($value)
 * @method static Builder|BigcommerceStoreUser whereIsOwner($value)
 * @method static Builder|BigcommerceStoreUser whereUpdatedAt($value)
 * @method static Builder|BigcommerceStoreUser whereUserId($value)
 * @property-read \App\Models\BigcommerceStore $store
 */
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
