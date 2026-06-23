<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barbers = [
            ['name' => 'Charly Martínez', 'email' => 'charly0620@barbershop.com', 'password' => 'Charly123'],
            ['name' => 'Manuel Rubio', 'email' => 'manny007@barbershop.com', 'password' => 'Manuel123'],
            ['name' => 'Carlos López', 'email' => 'test@example.com', 'password' => '12345678'],

        ];

        foreach ($barbers as $barberData) {
            $user = User::create([
                'name' => $barberData['name'],
                'email' => $barberData['email'],
                'password' => bcrypt($barberData['password']),
                'role' => 'barber'
            ]);

            Barber::create([
                'user_id' => $user->id,
                'description' => 'Especialista en cortes',
                'clients_count' => 0
            ]);
        }
    }
}
