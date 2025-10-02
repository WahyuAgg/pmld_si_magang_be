<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mitra>
 */
class MitraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_perusahaan' => $this->faker->company,
            'alamat' => $this->faker->address,
            'no_telp' => '08' . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->companyEmail,
            'website' => $this->faker->url,
            'bidang_usaha' => $this->faker->randomElement(['IT', 'Manufaktur', 'Konsultan', 'Pendidikan']),
            'deskripsi' => $this->faker->sentence(10),
        ];
    }
}
