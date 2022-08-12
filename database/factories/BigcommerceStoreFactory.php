<?php

namespace Database\Factories;

use App\Models\BigcommerceStore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BigcommerceStoreFactory extends Factory
{

    protected $model = BigcommerceStore::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'store_hash' => Str::random(10),
            'scope' => 'store_v2_content store_v2_default',
            'access_token' => encrypt(Str::random(150)),
            'installed' => true,
            'timezone_name' => $this->faker->timezone,
            'timezone_raw_offset' => $this->faker->randomNumber(),
            'timezone_dst_offset' => $this->faker->randomNumber(),
            'timezone_dst_correction' => $this->faker->boolean(),
            'trial_ends_at' => Carbon::now()->addDays(14),
        ];
    }

}
