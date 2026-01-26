<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dosbing;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosbing>
 */
class DosbingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => $this->faker->unique()->numerify('1980######'),
            'nama' => $this->faker->name,
        ];
    }
}
