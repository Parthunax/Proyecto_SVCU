@extends('layouts.app')

@section('title', 'Consulta de Personas')

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

        /* Table Styles */
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

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--border-color);
            object-fit: cover;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
            border: 1px solid #10b981;
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

        .detail-grid-single {
            display: grid;
            grid-template-columns: 1fr;
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

        .historial-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .historial-table th {
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            text-align: left;
        }

        .historial-table td {
            padding: 0.6rem 0.75rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.3);
            color: var(--text-main);
        }

        .detail-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
        }

        .detail-avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--text-muted);
            border: 3px solid var(--primary);
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
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
    <h1 class="page-title"><i class="fa-solid fa-users"></i> Consulta de Personas</h1>

    <div class="card" style="margin-bottom: 1.5rem;">
        <form action="" method="GET" class="search-container">
            <input type="text" name="q" class="search-input"
                placeholder="Buscar por número de documento, nombres o apellidos..." value="{{ request('q') }}">
            <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Género</th>
                        <th>Estatus Delictivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personas as $persona)
                        <tr>
                            <td>
                                @if($persona->foto_cara)
                                    <img src="{{ asset($persona->foto_cara) }}" alt="Foto" class="avatar">
                                @else
                                    <div class="avatar"
                                        style="display:flex; align-items:center; justify-content:center; color: var(--text-muted);">
                                        <i class="fa-solid fa-user"></i></div>
                                @endif
                            </td>
                            <td>{{ $persona->nun_documento }}</td>
                            <td>{{ $persona->Nombre }} {{ $persona->Paterno }}</td>
                            <td>{{ $persona->Genero == 'M' ? 'Masculino' : 'Femenino' }}</td>
                            <td>
                                @if($persona->historialDelictivo->count() > 0)
                                    <span class="badge badge-danger">Con Antecedentes</span>
                                @else
                                    <span class="badge badge-success">Sin Antecedentes</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn btn-secondary" style="padding: 0.5rem; font-size: 0.8rem;"
                                        title="Ver Detalle" onclick="openModal('modalConsulta-{{ $loop->index }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                No se encontraron resultados. Utiliza el buscador para consultar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $personas->links() }}</div>
        </div>
    </div>

    {{-- Modales de detalle fuera de la tabla --}}
    @foreach($personas as $persona)
        <div id="modalConsulta-{{ $loop->index }}" class="modal-overlay"
            onclick="if(event.target===this) closeModal('modalConsulta-{{ $loop->index }}')">
            <div class="modal-content" style="max-width: 950px;">
                <div class="modal-header">
                    <h3><i class="fa-solid fa-id-card"></i> Detalle de Persona</h3>
                    <button type="button" class="btn-close" onclick="closeModal('modalConsulta-{{ $loop->index }}')"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <!-- Header con foto y nombre -->
                    <div class="detail-header">
                        @if($persona->foto_cara)
                            <img src="{{ asset($persona->foto_cara) }}" alt="Foto" class="detail-avatar">
                        @else
                            <div class="detail-avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                        @endif
                        <div class="detail-header-info">
                            <h4>{{ $persona->Nombre }} {{ $persona->Paterno }} {{ $persona->Materno }}</h4>
                            <p>{{ $persona->nun_documento }}</p>
                        </div>
                        <div class="detail-header-status">
                            @if($persona->historialDelictivo->count() > 0)
                                <span class="badge badge-danger" style="margin-top: 0.25rem;">Con Antecedentes
                                    ({{ $persona->historialDelictivo->count() }})</span>
                            @else
                                <span class="badge badge-success" style="margin-top: 0.25rem;">Sin Antecedentes</span>
                            @endif
                        </div>
                    </div>

                    <!-- Dos paneles: Datos Personales (izq) + Dirección (der) -->
                    <div class="detail-two-panels">
                        <!-- Panel Izquierdo: Datos Personales -->
                        <div class="detail-panel">
                            <div class="detail-section-title"><i class="fa-solid fa-user"></i> Datos Personales</div>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="label">Documento</span>
                                    <span class="value">{{ $persona->nun_documento }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Género</span>
                                    <span class="value">{{ $persona->Genero == 'M' ? 'Masculino' : 'Femenino' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Estado Civil</span>
                                    <span class="value">{{ $persona->EstadoCivil ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Teléfono</span>
                                    <span class="value">{{ $persona->Telefono ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Nacimiento</span>
                                    <span class="value">{{ $persona->FechaNacimiento ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Derecho: Dirección -->
                        <div class="detail-panel">
                            <div class="detail-section-title"><i class="fa-solid fa-location-dot"></i> Dirección</div>
                            @if($persona->direccion)
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <span class="label">Estado</span>
                                        <span
                                            class="value">{{ $persona->direccion->parroquia->municipioObj->estadoObj->estado ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Municipio</span>
                                        <span
                                            class="value">{{ $persona->direccion->parroquia->municipioObj->municipio ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Parroquia</span>
                                        <span class="value">{{ $persona->direccion->parroquia->parroquia ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Localidad</span>
                                        <span class="value">{{ $persona->direccion->localidad ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Vivienda</span>
                                        <span class="value">{{ $persona->direccion->tipo_vivienda ?? 'N/A' }}
                                            ({{ $persona->direccion->nun_vivienda ?? 'S/N' }})</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="label">Ruta</span>
                                        <span class="value">{{ $persona->direccion->ruta ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            @else
                                <p style="color: var(--text-muted); font-size: 0.85rem;">Sin dirección registrada.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Historial Delictivo (ancho completo) -->
                    <div class="detail-section-title-full"><i class="fa-solid fa-gavel"></i> Historial Delictivo</div>
                    @if($persona->historialDelictivo->count() > 0)
                        <div style="overflow-x: auto;">
                            <table class="historial-table">
                                <thead>
                                    <tr>
                                        <th>Delito</th>
                                        <th>Tipo</th>
                                        <th>Cargo Penal</th>
                                        <th>Fecha</th>
                                        <th>Estatus</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($persona->historialDelictivo as $historial)
                                        <tr>
                                            <td>{{ $historial->delito->Nombre ?? 'N/A' }}</td>
                                            <td>{{ $historial->delito->Tipo ?? 'N/A' }}</td>
                                            <td>{{ $historial->delito->cargo_penal ?? 'N/A' }}</td>
                                            <td>{{ $historial->fecha_delito ?? 'N/A' }}</td>
                                            <td>
                                                @if($historial->estatus === 'activo')
                                                    <span class="badge badge-danger">Activo</span>
                                                @else
                                                    <span class="badge badge-success">{{ ucfirst($historial->estatus) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $historial->descripcion ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-check-circle"
                                style="color: #10b981;"></i> Esta persona no tiene antecedentes delictivos registrados.</p>
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