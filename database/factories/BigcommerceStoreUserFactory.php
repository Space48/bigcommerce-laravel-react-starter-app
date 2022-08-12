<?php

namespace Database\Factories;

use App\Models\BigcommerceStoreUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BigcommerceStoreUserFactory extends Factory
{

    protected $model = BigcommerceStoreUser::class;

    public function definition()
    {
        return [
            'is_owner' => $this->faker->boolean(),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
