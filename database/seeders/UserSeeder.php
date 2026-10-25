<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'frsawiris@manuscriptcataloguer'],
            [
                'name' => 'Fr. Sawiris',
                'password' => 'Gundi..ticsh19',
                'email_verified_at' => now(),
            ]
        );
    }
}
