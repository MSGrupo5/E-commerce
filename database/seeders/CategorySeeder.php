<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Tarjetas de video',
            'slug' => Str::slug('Tarjetas de video'),
        ]);

        Category::create([
            'name' => 'Motherboards',
            'slug' => Str::slug('Motherboards'),
        ]);

        Category::create([
            'name' => 'Monitores',
            'slug' => Str::slug('Monitores'),
        ]);

        Category::create([
            'name' => 'Perifericos',
            'slug' => Str::slug('Perifericos'),
        ]);

        Category::create([
            'name' => 'Audio',
            'slug' => Str::slug('Audio'),
        ]);

        Category::create([
            'name' => 'Combos',
            'slug' => Str::slug('Combos'),
        ]);
    }
}