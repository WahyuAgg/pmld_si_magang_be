<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Supervisor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supervisor>
 */
class SupervisorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'perusahaan_id' => Mitra::factory(),
            'nama_supervisor' => $this->faker->name,
            'jabatan' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'no_hp' => '08' . fake()->numerify('+62########'),
        ];
    }
}
