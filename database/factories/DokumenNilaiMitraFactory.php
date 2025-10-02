<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Magang;
use App\Models\Supervisor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DokumenNilaiMitra>
 */
class DokumenNilaiMitraFactory extends Factory
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
            'nama_file' => $this->faker->word . '.pdf',
            'path_file' => 'storage/penilaian/' . $this->faker->uuid . '.pdf',
            'jenis_dokumen' => $this->faker->randomElement(['penilaian', 'sertifikat', 'laporan']),
            'keterangan' => $this->faker->sentence(8),
        ];
    }
}
