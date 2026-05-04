@extends('layouts.app')

@section('title', 'Consulta de Vehículos')

@section('styles')
    <style>
        .search-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .search-input {
            flex-grow: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 2rem;
            border: 1px solid var(--border-color);
            background-color: var(--bg-card);
            color: var(--text-main);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }

        .btn-search {
            padding: 0.875rem 2rem;
            border-radius: 2rem;
            border: none;
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-search:hover {
            background-color: var(--primary-hover);
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            padding: 1rem;
            border-bottom: 2px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            color: var(--text-main);
            vertical-align: middle;
        }

        .data-table tr:hover td {
            background-color: rgba(59, 130, 246, 0.05);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid #f59e0b;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid #10b981;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Detail modal styles */
        .detail-two-panels {
            display: flex;
            gap: 1.5rem;
        }

        .detail-panel {
            flex: 1;
            min-width: 0;
        }

        .detail-panel+.detail-panel {
            border-left: 1px solid var(--border-color);
            padding-left: 1.5rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .detail-item .label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .detail-item .value {
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .detail-section-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-section-title-full {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary);
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reportes-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .reportes-table th {
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            text-align: left;
        }

        .reportes-table td {
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.3);
            color: var(--text-main);
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-icon-vehicle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .detail-header-info h4 {
            font-size: 1.05rem;
            font-weight: 600;
        }

        .detail-header-info p {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .detail-header-status {
            margin-left: auto;
            display: flex;
            align-items: center;
            transform: scale(1.4);
            transform-origin: right;
            padding-right: 1.5rem;
            filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.3));
            animation: pulse-status 2s infinite;
        }
    </style>
@endsection

@section('content')
    <h1 class="page-title"><i class="fa-solid fa-car"></i> Consulta de Vehículos</h1>

    <div class="card" style="margin-bottom: 1.5rem;">
        <form action="" method="GET" class="search-container">
            <input type="text" name="q" class="search-input" placeholder="Buscar por placa, modelo o color..."
                value="{{ request('q') }}">
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca y Modelo</th>
                        <th>Color</th>
                        <th>Año</th>
                        <th>Propietario</th>
                        <th>Estatus / Reporte</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td><strong>{{ $vehiculo->nun_placa }}</strong></td>
                            <td>{{ $vehiculo->marcaObj->nombre_marca ?? 'N/A' }} {{ $vehiculo->modelo }}</td>
                            <td>{{ $vehiculo->color }}</td>
                            <td>{{ $vehiculo->año }}</td>
                            <td>{{ $vehiculo->propietarioObj->Nombre ?? 'N/A' }} {{ $vehiculo->propietarioObj->Paterno ?? '' }}
                            </td>
                            <td>
                                @if($vehiculo->reportes->count() > 0)
                                    <span
                                        class="badge badge-warning">{{ strtoupper($vehiculo->reportes->first()->tipo_reporte) }}</span>
                                @else
                                    <span class="badge badge-success">Limpio</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn btn-secondary" style="padding: 0.5rem; font-size: 0.8rem;"
                                        title="Ver Detalle" onclick="openModal('modalVehiculo-{{ $loop->index }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                No se encontraron resultados. Utiliza el buscador para consultar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $vehiculos->links() }}</div>
        </div>
    </div>

    {{-- Modales de detalle fuera de la tabla --}}
    @foreach($vehiculos as $vehiculo)
        <div id="modalVehiculo-{{ $loop->index }}" class="modal-overlay"
            onclick="if(event.target===this) closeModal('modalVehiculo-{{ $loop->index }}')">
            <div class="modal-content" style="max-width: 950px;">
                <div class="modal-header">
                    <h3><i class="fa-solid fa-car"></i> Detalle de Vehículo</h3>
                    <button type="button" class="btn-close" onclick="closeModal('modalVehiculo-{{ $loop->index }}')"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <!-- Header con icono y placa -->
                    <div class="detail-header">
                        <div class="detail-icon-vehicle"><i class="fa-solid fa-car"></i></div>
                        <div class="detail-header-info">
                            <h4>{{ $vehiculo->nun_placa }}</h4>
                            <p>{{ $vehiculo->marcaObj->nombre_marca ?? 'N/A' }} {{ $vehiculo->modelo }} — {{ $vehiculo->color }}
                                ({{ $vehiculo->año }})</p>
                        </div>
                        <div class="detail-header-status">
                            @if($vehiculo->reportes->count() > 0)
                                <span class="badge badge-warning" style="margin-top: 0.25rem;">Con Reportes
                                    ({{ $vehiculo->reportes->count() }})</span>
                            @else
                                <span class="badge badge-success" style="margin-top: 0.25rem;">Sin Reportes</span>
                            @endif
                        </div>
                    </div>

                    <!-- Dos paneles: Datos del Vehículo (izq) + Propietario y Marca (der) -->
                    <div class="detail-two-panels">
                        <!-- Panel Izquierdo: Datos del Vehículo -->
                        <div class="detail-panel">
                            <div class="detail-section-title"><i class="fa-solid fa-car-side"></i> Datos del Vehículo</div>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="label">Placa</span>
                                    <span class="value">{{ $vehiculo->nun_placa }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Tipo</span>
                                    <span class="value">{{ $vehiculo->tipo_vehiculo ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Modelo</span>
                                    <span class="value">{{ $vehiculo->modelo ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Color</span>
                                    <span class="value">{{ $vehiculo->color ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Año</span>
                                    <span class="value">{{ $vehiculo->año ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Serial Carrocería</span>
                                    <span class="value">{{ $vehiculo->serial_carroceria ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Derecho: Marca + Propietario -->
                        <div class="detail-panel">
                            <div class="detail-section-title"><i class="fa-solid fa-tag"></i> Marca</div>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="label">Nombre</span>
                                    <span class="value">{{ $vehiculo->marcaObj->nombre_marca ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Descripción</span>
                                    <span class="value">{{ $vehiculo->marcaObj->descripcion ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="detail-section-title" style="margin-top: 1.25rem;"><i class="fa-solid fa-user"></i>
                                Propietario</div>
                            @if($vehiculo->propietarioObj)
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <span class="label">Documento</span>
                                        <span class="value">{{ $vehiculo->propietarioObj->nun_documento }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Nombre</span>
                                        <span class="value">{{ $vehiculo->propietarioObj->Nombre }}
                                            {{ $vehiculo->propietarioObj->Paterno }}</span>
                                    </div>
                                </div>
                            @else
                                <p style="color: var(--text-muted); font-size: 0.85rem;">Sin propietario registrado.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Reportes (ancho completo) -->
                    <div class="detail-section-title-full"><i class="fa-solid fa-flag"></i> Reportes</div>
                    @if($vehiculo->reportes->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="reportes-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo de Reporte</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehiculo->reportes as $reporte)
                                        <tr>
                                            <td>{{ $reporte->reporte_id }}</td>
                                            <td>
                                                <span class="badge badge-warning">{{ strtoupper($reporte->tipo_reporte) }}</span>
                                            </td>
                                            <td>{{ $reporte->fecha_reporte }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-check-circle"
                                style="color: #10b981;"></i> Este vehículo no tiene reportes registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
@endsection