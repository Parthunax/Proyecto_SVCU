@extends('layouts.app')

@section('title', 'Inicio')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: linear-gradient(135deg, rgba(30,41,59,0.8), rgba(15,23,42,0.9));
        border-left: 4px solid var(--primary);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: rgba(59, 130, 246, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary);
    }

    .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-info p {
        color: var(--text-muted);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .welcome-card {
        background: url('https://images.unsplash.com/photo-1541888062955-802521199a09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80') center/cover;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .welcome-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to right, rgba(15,23,42,0.95) 30%, rgba(15,23,42,0.6));
    }

    .welcome-content {
        position: relative;
        z-index: 1;
        padding: 2rem;
    }

    .welcome-content h2 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
    }
</style>
@endsection

@section('content')
    <h1 class="page-title"><i class="fa-solid fa-gauge-high"></i> Panel de Control</h1>

    <div class="card welcome-card">
        <div class="welcome-overlay"></div>
        <div class="welcome-content">
            <h2>Bienvenido al Sistema SIBOT</h2>
            <p>Hola, <strong>{{ Auth::user()->usuario }}</strong>. Has iniciado sesión como {{ Auth::user()->rolObj->nombre_rol ?? 'Usuario' }}.</p>
            <p style="margin-top: 10px; color: var(--text-muted); font-size: 0.85rem;">
                Último acceso registrado: {{ Auth::user()->ultimo_acceso ? Auth::user()->ultimo_acceso->format('d/m/Y h:i A') : 'Primera vez' }}
            </p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="card stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>---</h3>
                <p>Personas Registradas</p>
            </div>
        </div>

        <div class="card stat-card" style="border-left-color: #10b981;">
            <div class="stat-icon" style="color: #10b981; background-color: rgba(16, 185, 129, 0.1);">
                <i class="fa-solid fa-car"></i>
            </div>
            <div class="stat-info">
                <h3>---</h3>
                <p>Vehículos Registrados</p>
            </div>
        </div>

        <div class="card stat-card" style="border-left-color: #f59e0b;">
            <div class="stat-icon" style="color: #f59e0b; background-color: rgba(245, 158, 11, 0.1);">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="stat-info">
                <h3>---</h3>
                <p>Reportes Activos</p>
            </div>
        </div>
    </div>
@endsection
