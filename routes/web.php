<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Consultas\ConsultaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', \App\Http\Middleware\CheckActiveUser::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Módulo 1: Consultas
    Route::prefix('consultas')->name('consultas.')->group(function () {
        Route::get('/personas', [ConsultaController::class, 'personas'])->name('personas');
        Route::get('/vehiculos', [ConsultaController::class, 'vehiculos'])->name('vehiculos');
    });

    // Módulo 2 y 3: Registro (Personas, Vehículos y sub-módulos)
    Route::middleware([\App\Http\Middleware\CheckRole::class])->group(function () {
        Route::get('/api/municipios/{estado_id}', [App\Http\Controllers\Ajax\AjaxController::class, 'getMunicipios']);
        Route::get('/api/parroquias/{municipio_id}', [App\Http\Controllers\Ajax\AjaxController::class, 'getParroquias']);

        Route::resource('personas', App\Http\Controllers\Registro\PersonaController::class);
        Route::resource('delitos', App\Http\Controllers\Registro\DelitoController::class);
        Route::resource('historial', App\Http\Controllers\Registro\HistorialDelictivoController::class);

        Route::resource('vehiculos', App\Http\Controllers\Registro\VehiculoController::class);
        Route::resource('marcas', App\Http\Controllers\Registro\MarcaVehiculoController::class);
        Route::resource('reportes', App\Http\Controllers\Registro\ReporteVehiculoController::class);
    });

    // Módulo 5.1: Configuraciones (Para todos los autenticados)
    Route::get('/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'update'])->name('configuracion.update');

    // Módulo 4 y 5.2/5.3: Rutas exclusivas para el Comisario
    Route::middleware([\App\Http\Middleware\CheckJefe::class])->group(function () {
        // Módulo 4: Ubicaciones
        Route::get('/ubicaciones', [App\Http\Controllers\Ubicaciones\UbicacionController::class, 'index'])->name('ubicaciones.index');
        Route::resource('estados', App\Http\Controllers\Ubicaciones\EstadoController::class)->except(['index', 'create', 'show', 'edit']);
        Route::resource('municipios', App\Http\Controllers\Ubicaciones\MunicipioController::class)->except(['index', 'create', 'show', 'edit']);
        Route::resource('parroquias', App\Http\Controllers\Ubicaciones\ParroquiaController::class)->except(['index', 'create', 'show', 'edit']);

        // Módulo 5: Gestión
        Route::resource('policias', App\Http\Controllers\Gestion\PoliciaController::class);
        Route::resource('usuarios', App\Http\Controllers\Gestion\UsuarioController::class);
    });
});
