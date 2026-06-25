<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@paypal.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        Wallet::create([
            'user_id' => $admin->id, 
            'balance' => 0.00,
            'currency' => 'USD'
        ]);

        $profe = User::create([
            'name' => 'angel',
            'email' => 'profe@test.com',
            'password' => Hash::make('password'), 
        ]);

        // Le asignamos $1000 dólares iniciales directamente en la base de datos
        $profe->wallet()->create([
            'balance' => 100000000.00,
            'currency' => 'USD'
        ]);

        // Crear al usuario "Alumno"
        $alumno = User::create([
            'name' => 'Alumno de Prueba',
            'email' => 'alumno@test.com',
            'password' => Hash::make('password'), // La contraseña será: password
        ]);

        // Le asignamos $0 dólares iniciales
        $alumno->wallet()->create([
            'balance' => 0.00,
            'currency' => 'USD'
        ]);
    }
}