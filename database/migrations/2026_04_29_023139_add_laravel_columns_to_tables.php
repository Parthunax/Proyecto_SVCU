<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix for MySQL STRICT mode error with duplicated enum values in usuarios
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE usuarios MODIFY COLUMN estadus ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'");

        $tables = [
            'roles', 'usuarios', 'policia', 'estados', 'municipios', 
            'parroquias', 'direccion', 'persona', 'delito', 
            'historial_delictivo', 'marcas_vehiculos', 'vehiculo', 
            'reporte_vehiculo'
        ];

        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'created_at')) {
                    $table->timestamps();
                }
                if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'roles', 'usuarios', 'policia', 'estados', 'municipios', 
            'parroquias', 'direccion', 'persona', 'delito', 
            'historial_delictivo', 'marcas_vehiculos', 'vehiculo', 
            'reporte_vehiculo'
        ];

        foreach ($tables as $t) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropSoftDeletes();
                $table->dropTimestamps();
            });
        }
    }
};
