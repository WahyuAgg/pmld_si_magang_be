<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Magang;
use App\Models\DokumenMagang;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DokumenMagang>
 */
class DokumenMagangFactory extends Factory
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
            'jenis_dokumen' => $this->faker->randomElement(['surat_penerimaan', 'pra_krs', 'laporan_magang']),
            'nama_file' => $this->faker->word . '.pdf',
            'path_file' => 'storage/dokumen/' . $this->faker->uuid . '.pdf',
            'ukuran_file' => $this->faker->numberBetween(50000, 5000000),
            'status_dokumen' => $this->faker->randomElement(['draft', 'submitted', 'approved', 'rejected']),
            'keterangan' => $this->faker->sentence(6),
        ];
    }
}
