<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Magang;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalPresentasi>
 */
class JadwalPresentasiFactory extends Factory
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
            'tanggal_presentasi' => $this->faker->dateTimeBetween('+1 week', '+2 months'),
            'waktu_mulai' => $this->faker->time('H:i'),
            'waktu_selesai' => $this->faker->time('H:i'),
            'tempat' => $this->faker->company,
            'ruangan' => 'R-' . $this->faker->numberBetween(101, 999),
            'keterangan' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['terjadwal', 'selesai', 'dibatalkan']),
            'created_by' => User::factory(),
        ];
    }
}
