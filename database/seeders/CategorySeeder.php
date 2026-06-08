<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Tarjetas de video']);
        Category::create(['name' => 'Motherboards']);
        Category::create(['name' => 'Monitores']);
        Category::create(['name' => 'Perifericos']);
        Category::create(['name' => 'Audio']);
        Category::create(['name' => 'Combos']);
    }
}
