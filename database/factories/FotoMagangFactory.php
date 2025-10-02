<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Logbook;
use App\Models\FotoMagang;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FotoMagang>
 */
class FotoMagangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logbook_id' => Logbook::factory(),
            'nama_file' => $this->faker->word . '.jpg',
            'path_file' => 'storage/foto/' . $this->faker->uuid . '.jpg',
            'keterangan' => $this->faker->sentence(5),
        ];
    }
}
