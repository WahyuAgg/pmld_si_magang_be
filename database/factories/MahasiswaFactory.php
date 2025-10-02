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
            'email'        => $this->faker->unique()->safeEmail,
            'no_hp' => '08' . $this->faker->numerify('##########'),
            'angkatan'     => $this->faker->numberBetween(2020, 2025),
            'semester'     => $this->faker->numberBetween(1, 8),
            'alamat'       => $this->faker->address,
            'foto_profile' => null,
            'status_aktif' => $this->faker->boolean,
        ];
    }
}
