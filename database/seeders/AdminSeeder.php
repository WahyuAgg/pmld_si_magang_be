<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin', // ✅ WAJIB ada
            'email' => 'admin@example.com',
            'password' => bcrypt('Osn1qtzx5wM7HPuTxl4cFp1S'),
            'role' => 'admin',
        ]);




    }
}
