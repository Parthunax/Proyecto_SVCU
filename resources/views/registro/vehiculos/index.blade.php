@extends('layouts.app')

@section('title', 'Gestión de Vehículos')

@section('styles')
<style>
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
    .form-control { width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.5rem; color: var(--text-main); }
    .table-container { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; text-align: left; }
    .data-table th { padding: 1rem; border-bottom: 2px solid var(--border-color); color: var(--text-muted); text-transform: uppercase; font-size: 0.875rem; }
    .data-table td { padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.5); }
    .action-btns { display: flex; gap: 5px; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
</style>
@endsection

@section('content')
    <div class="page-title" style="display: flex; justify-content: space-between; align-items: center;">
        <div><i class="fa-solid fa-car"></i> Gestión de Vehículos</div>
        <button class="btn btn-primary" onclick="openModal('modalCreate')">
            <i class="fa-solid fa-plus"></i> Registrar Vehículo
        </button>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Modelo / Color</th>
                        <th>Propietario</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->nun_placa }}</td>
                        <td>{{ $vehiculo->modelo }} - {{ $vehiculo->color }} ({{ $vehiculo->año }})</td>
                        <td>{{ $vehiculo->propietarioObj->Nombre ?? 'Desconocido' }}</td>
                        <td>{{ $vehiculo->marcaObj->nombre_marca ?? 'Desconocida' }}</td>
                        <td>{{ $vehiculo->tipo_vehiculo }}</td>
                        <td class="action-btns">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Ver Detalle" onclick="openModal('modalView-{{ $vehiculo->nun_placa }}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Editar" onclick="openModal('modalEdit-{{ $vehiculo->nun_placa }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('vehiculos.destroy', $vehiculo->nun_placa) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 0.5rem; color: #ef4444;" title="Eliminar" onclick="return confirm('¿Eliminar registro?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Ver -->
                    <div id="modalView-{{ $vehiculo->nun_placa }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 700px;">
                            <div class="modal-header">
                                <h3>Detalle del Vehículo</h3>
                                <button class="btn-close" onclick="closeModal('modalView-{{ $vehiculo->nun_placa }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-grid">
                                    <p><strong>Placa:</strong> {{ $vehiculo->nun_placa }}</p>>
                                <p><strong>Tipo:</strong> {{ $vehiculo->tipo_vehiculo }}</p>
                                <p><strong>Marca:</strong> {{ $vehiculo->marcaObj->nombre_marca ?? 'N/A' }}</p>
                                <p><strong>Modelo:</strong> {{ $vehiculo->modelo }}</p>
                                <p><strong>Color:</strong> {{ $vehiculo->color }}</p>
                                <p><strong>Año:</strong> {{ $vehiculo->año }}</p>
                                    <p><strong>Serial Carrocería:</strong> {{ $vehiculo->serial_carroceria }}</p>
                                    <p><strong>Propietario:</strong> {{ $vehiculo->propietarioObj->Nombre ?? 'N/A' }} ({{ $vehiculo->propietario }})</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                    <div id="modalEdit-{{ $vehiculo->nun_placa }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 700px;">
                            <div class="modal-header">
                                <h3>Editar Vehículo</h3>
                                <button class="btn-close" onclick="closeModal('modalEdit-{{ $vehiculo->nun_placa }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form action="{{ route('vehiculos.update', $vehiculo->nun_placa) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-grid">
                                        <div class="form-group">
                                        <label>Placa del Vehículo (No editable)</label>
                                        <input type="text" class="form-control" value="{{ $vehiculo->nun_placa }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipo de Vehículo *</label>
                                        <select name="tipo_vehiculo" class="form-control" required>
                                            <option value="automovil" {{ $vehiculo->tipo_vehiculo == 'automovil' ? 'selected' : '' }}>Automóvil</option>
                                            <option value="motocicleta" {{ $vehiculo->tipo_vehiculo == 'motocicleta' ? 'selected' : '' }}>Motocicleta</option>
                                            <option value="camioneta" {{ $vehiculo->tipo_vehiculo == 'camioneta' ? 'selected' : '' }}>Camioneta</option>
                                            <option value="camion" {{ $vehiculo->tipo_vehiculo == 'camion' ? 'selected' : '' }}>Camión</option>
                                            <option value="autobus" {{ $vehiculo->tipo_vehiculo == 'autobus' ? 'selected' : '' }}>Autobús</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Marca *</label>
                                        <select name="marca" class="form-control" required>
                                            @foreach($marcas as $marca)
                                            <option value="{{ $marca->marca_id }}" {{ $vehiculo->marca == $marca->marca_id ? 'selected' : '' }}>{{ $marca->nombre_marca }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Propietario *</label>
                                        <select name="propietario" class="form-control" required>
                                            @foreach($personas as $persona)
                                            <option value="{{ $persona->nun_documento }}" {{ $vehiculo->propietario == $persona->nun_documento ? 'selected' : '' }}>{{ $persona->nun_documento }} - {{ $persona->Nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Modelo *</label>
                                        <input type="text" name="modelo" class="form-control" value="{{ $vehiculo->modelo }}" maxlength="100" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Color *</label>
                                        <input type="text" name="color" class="form-control" value="{{ $vehiculo->color }}" maxlength="50" required>
                                    </div>
                                        <div class="form-group">
                                            <label>Año *</label>
                                            <input type="number" name="año" class="form-control" value="{{ $vehiculo->año }}" min="1900" max="{{ date('Y')+1 }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEdit-{{ $vehiculo->nun_placa }}')">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">Sin registros.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $vehiculos->links() }}</div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="modalCreate" class="modal-overlay">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h3>Nuevo Vehículo</h3>
                <button class="btn-close" onclick="closeModal('modalCreate')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('vehiculos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                        <label>Placa del Vehículo *</label>
                        <input type="text" name="nun_placa" class="form-control" maxlength="7" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Vehículo *</label>
                        <select name="tipo_vehiculo" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="automovil">Automóvil</option>
                            <option value="motocicleta">Motocicleta</option>
                            <option value="camioneta">Camioneta</option>
                            <option value="camion">Camión</option>
                            <option value="autobus">Autobús</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marca *</label>
                        <select name="marca" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($marcas as $marca)
                            <option value="{{ $marca->marca_id }}">{{ $marca->nombre_marca }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Propietario *</label>
                        <select name="propietario" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($personas as $persona)
                            <option value="{{ $persona->nun_documento }}">{{ $persona->nun_documento }} - {{ $persona->Nombre }} {{ $persona->Paterno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Modelo *</label>
                        <input type="text" name="modelo" class="form-control" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label>Color *</label>
                        <input type="text" name="color" class="form-control" maxlength="50" required>
                    </div>
                    <div class="form-group">
                        <label>Año *</label>
                        <input type="number" name="año" class="form-control" min="1900" max="{{ date('Y')+1 }}" required>
                    </div>
                        <div class="form-group">
                            <label>Serial de Carrocería *</label>
                            <input type="text" name="serial_carroceria" class="form-control" maxlength="100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalCreate')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
</script>
@endsection
