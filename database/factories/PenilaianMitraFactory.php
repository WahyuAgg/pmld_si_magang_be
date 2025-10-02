<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Magang;
use App\Models\Supervisor;
use App\Models\NilaiMitra;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NilaiMitra>
 */
class PenilaianMitraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'magang_id' => Magang::factory(),
            'supervisor_id' => Supervisor::factory(),
            'nilai' => $this->faker->randomFloat(2, 60, 100),
            'keterangan' => $this->faker->sentence(8),
        ];
    }
}
