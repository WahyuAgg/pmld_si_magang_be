<?php

namespace Database\Factories;

use App\Models\Dosbing;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mahasiswa;
use App\Models\Mitra;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Magang>
 */
class MagangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mahasiswa_id' => Mahasiswa::factory(),
            'perusahaan_id' => Mitra::factory(),
            'dosbing_id' => Dosbing::factory(),
            'semester_magang' => $this->faker->numberBetween(5, 8),
            'jumlah_magang_ke' => $this->faker->numberBetween(1, 2),
            'role_magang' => $this->faker->jobTitle,
            'jobdesk' => $this->faker->sentence(8),
            'tanggal_mulai' => $this->faker->date(),
            'tanggal_selesai' => $this->faker->date(),
            'periode_bulan' => $this->faker->numberBetween(3, 6),
            'status_magang' => $this->faker->randomElement(['draft','berlangsung','selesai','ditolak']),
        ];
    }
}
