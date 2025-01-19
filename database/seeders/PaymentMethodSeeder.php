<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::query()->delete();
        PaymentMethod::create([
            'name' => 'paypal'
        ]);
        PaymentMethod::create([
            'name' => 'credit_card'
        ]);    
    }
}
