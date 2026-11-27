<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $usuarios = [
            ['name' => 'Ana Solicitante', 'email' => 'solicitante@empresa.com', 'rol' => 'solicitante'],
            ['name' => 'Beto Compras', 'email' => 'compras@empresa.com', 'rol' => 'compras'],
            ['name' => 'Carla Gerencia', 'email' => 'gerencia@empresa.com', 'rol' => 'gerencia'],
            ['name' => 'Dario Contabilidad', 'email' => 'contabilidad@empresa.com', 'rol' => 'contabilidad'],
        ];

        foreach ($usuarios as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => bcrypt('password')]
            );
            $user->assignRole($u['rol']);
        }

        Proveedor::firstOrCreate(['nombre' => 'Proveedor Ejemplo S.A. de C.V.'], [
            'rfc' => 'PEJ850101ABC',
            'contacto' => 'Juan Pérez',
            'telefono' => '4771234567',
            'email' => 'contacto@proveedorejemplo.com',
        ]);
    }
}
