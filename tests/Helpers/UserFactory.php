<?php

namespace Saucebase\LaravelPlaywright\Tests\Helpers;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserModel>
 */
class UserFactory extends Factory
{

    protected $model = UserModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }

    public function admin(): static
    {
        return $this->state([
            'name' => 'Admin User',
        ]);
    }

}