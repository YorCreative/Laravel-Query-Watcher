<?php

namespace YorCreative\QueryWatcher\Tests\Utility\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use YorCreative\QueryWatcher\Tests\Utility\Models\DemoOwner;

/**
 * @extends Factory
 */
class DemoOwnerFactory extends Factory
{
    protected $model = DemoOwner::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
        ];
    }
}
