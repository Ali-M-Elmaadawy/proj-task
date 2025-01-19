<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::query()->delete();
        for ($i=11; $i < 17; $i++) { 
            Product::create([
                'name' => 'iphone '.$i,
                'qty'  => '10',
                'price' => $i.'000'
            ]);
        }
    }
}
