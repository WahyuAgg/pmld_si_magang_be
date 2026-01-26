<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(), // pastikan user dengan ID ini ada di tabel users
            'nim'          => $this->faker->unique()->numerify('2023####'),
            'nama'         => $this->faker->name,
            'angkatan'     => $this->faker->numberBetween(2023, 2025),
        ];
    }
}
