<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Policia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if roles exist, if not create them
        if (Rol::count() == 0) {
            Rol::create(['nombre_rol' => 'oficial', 'descripcion' => 'puede consultar']);
            Rol::create(['nombre_rol' => 'sipol', 'descripcion' => 'puede registrar']);
            Rol::create(['nombre_rol' => 'comisario', 'descripcion' => 'acceso completo']);
        }

        // Create Test Admin User (Comisario)
        $comisarioRole = Rol::where('nombre_rol', 'comisario')->first();
        if ($comisarioRole) {
            $user = Usuario::firstOrCreate(
                ['usuario' => 'admin'],
                [
                    'nun_documento' => '12345678',
                    'Contrasena' => Hash::make('admin123'),
                    'rol' => $comisarioRole->rol_id,
                    'estadus' => 'activo',
                    'ultimo_acceso' => now()
                ]
            );

            Policia::firstOrCreate(
                ['usuario_id' => $user->usuario_id],
                [
                    'nun_documento' => '12345678',
                    'especialidad' => 'Informática',
                    'Grado' => 'Comisario'
                ]
            );
        }
    }
}
