<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class BigcommerceStore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token',
        'country',
        'currency',
        'domain',
        'name',
        'scope',
        'store_hash',
        'installed',
        'plan_is_trial',
        'plan_level',
        'plan_name',
        'status',
        'store_id',
        'timezone_name',
        'timezone_raw_offset',
        'timezone_dst_offset',
        'timezone_dst_correction',
        'mapbox_api_key'
    ];

    protected $appends = [
        'active',
        'has_free_access',
        'on_trial',
        'sandbox',
        'subscribed',
        'timezone_offset',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get users for a BigCommerce Store
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'bigcommerce_store_users')->using(BigcommerceStoreUser::class);
    }

    public function store_users()
    {
        return $this->hasMany(BigcommerceStoreUser::class);
    }

    /**
     * @return BelongsToMany
     */
    public function owners()
    {
        return $this->users()->where('is_owner', true);
    }

    /**
     * @return User|null
     */
    public function getOwner()
    {
        return $this->owners->first();
    }

    /**
     * Scope a query to only include BigCommerce stores with a specific hash
     *
     * @param Builder $query
     * @param string $storeHash
     * @return Builder
     */
    public function scopeWithStoreHash(Builder $query, string $storeHash)
    {
        return $query->where('store_hash', $storeHash);
    }

    /**
     * Scope a query to only include BigCommerce stores with a specific store_id
     *
     * @param Builder $query
     * @param int $storeId
     * @return Builder
     */
    public function scopeWithStoreId(Builder $query, int $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    /**
     * Scope a query to just installed stores.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInstalled(Builder $query)
    {
        return $query->where('installed', true);
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->attributes['domain'];
    }

    /**
     * Set access token
     *
     * @param string $value
     * @return void
     */
    public function setAccessTokenAttribute(string $value)
    {
        $this->attributes['access_token'] = encrypt($value);
    }

    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessTokenAttribute()
    {
        return decrypt($this->attributes['access_token']);
    }

    /**
     * Get timezone offset.
     *
     * @return int|null
     */
    public function getTimezoneOffsetAttribute()
    {
        if (is_null($this->timezone_raw_offset) ||
            is_null($this->timezone_dst_offset) ||
            is_null($this->timezone_dst_correction)) {
            return null;
        }

        $offset = $this->timezone_raw_offset;
        if ($this->timezone_dst_correction) {
            $offset += $this->timezone_dst_offset;
        }

        return $offset;
    }

    public function getRouteKeyName()
    {
        return 'store_hash';
    }

    /**
     * Generate a link that directs user to the app within their BC admin
     * @return string
     */
    public function getAppDeepLink()
    {
        return sprintf(
            "https://store-%s.mybigcommerce.com/manage/app/%s",
            $this->store_hash,
            config('bigcommerce.app_id')
        );
    }

    /**
     * Get store hash from 'stores/${store_hash' string that BigCommerce regularly
     * use
     *
     * @throws InvalidArgumentException
     */
    public static function getStoreHashFromContext(string $context): string
    {
        $parts = explode('/', $context);
        if (isset($parts[1])) {
            return $parts[1];
        }

        throw new InvalidArgumentException(
            'Provided context is not in expected format of `stores/$store_hash`'
        );
    }

    public function uri(): string
    {
        return sprintf('https://store-%s.mybigcommerce.com', $this->store_hash);
    }
}
