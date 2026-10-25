<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Biblical', 'description' => 'Biblical texts and manuscripts'],
            ['name' => 'Liturgical', 'description' => 'Liturgical texts and rites'],
            ['name' => 'Theological', 'description' => 'Theological treatises and commentaries'],
            ['name' => 'Miscellaneous', 'description' => 'Miscellaneous manuscripts and texts'],
        ];

        foreach ($genres as $genre) {
            Genre::firstOrCreate(
                ['name' => $genre['name']],
                ['description' => $genre['description']]
            );
        }
    }
}
