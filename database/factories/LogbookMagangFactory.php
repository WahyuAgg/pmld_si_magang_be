<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Magang;
use App\Models\Logbook;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logbook>
 */
class LogbookMagangFactory extends Factory
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
            'tanggal_kegiatan' => $this->faker->date(),
            'kegiatan' => $this->faker->sentence(6),
            'deskripsi_kegiatan' => $this->faker->paragraph(2),
        ];
    }
}
