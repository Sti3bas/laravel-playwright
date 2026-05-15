<?php

namespace Saucebase\LaravelPlaywright\Tests\Helpers;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PostModel>
 */
class PostFactory extends Factory
{

    protected $model = PostModel::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
        ];
    }

}
