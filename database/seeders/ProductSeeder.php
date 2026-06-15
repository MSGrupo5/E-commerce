<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = [];
        for ($i = 1; $i <= 3; $i++) {
            $sellers[] = User::factory()->create([
                'name' => "Vendedor $i",
                'role' => 'usuario',
            ]);
        }

        Product::factory(25)
            ->sequence(
                ['user_id' => $sellers[0]->id],
                ['user_id' => $sellers[1]->id],
                ['user_id' => $sellers[2]->id],
            )
            ->create();
    }
}
