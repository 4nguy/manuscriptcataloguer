<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Collection::firstOrCreate(
            ['name' => 'St Paul Manuscripts'],
            ['description' => 'Collection of St Paul Manuscripts']
        );
    }
}
