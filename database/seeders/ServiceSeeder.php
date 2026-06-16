<?php

namespace Database\Seeders;
use App\Models\Service;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Corte clásico', 'duration_minutes' => 30, 'price' => 20.00],
            ['name' => 'Corte + Barba', 'duration_minutes' => 20, 'price' => 35.00],
            ['name' => 'Fade moderno', 'duration_minutes' => 25, 'price' => 30.00],
            ['name' => 'Afeitado premium', 'duration_minutes' => 45, 'price' => 25.00],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
