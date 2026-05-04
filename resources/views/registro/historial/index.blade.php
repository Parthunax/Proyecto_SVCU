@extends('layouts.app')

@section('title', 'Historial Delictivo')

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
        <div><i class="fa-solid fa-list"></i> Historial Delictivo</div>
        <button class="btn btn-primary" onclick="openModal('modalCreate')">
            <i class="fa-solid fa-plus"></i> Registrar Historial
        </button>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Persona (Cédula)</th>
                        <th>Delito</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historiales as $historial)
                    <tr>
                        <td>{{ $historial->historial_id }}</td>
                        <td>{{ $historial->persona->Nombre ?? 'N/A' }} ({{ $historial->persona_id }})</td>
                        <td>{{ $historial->delito->Nombre ?? 'N/A' }}</td>
                        <td>{{ $historial->fecha_delito }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $historial->estatus)) }}</td>
                        <td class="action-btns">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Ver Detalle" onclick="openModal('modalView-{{ $historial->historial_id }}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Editar" onclick="openModal('modalEdit-{{ $historial->historial_id }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('historial.destroy', $historial->historial_id) }}" method="POST" style="display:inline;">
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
                    <div id="modalView-{{ $historial->historial_id }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 800px;">
                            <div class="modal-header">
                                <h3><i class="fa-solid fa-list"></i> Detalle del Historial</h3>
                                <button class="btn-close" onclick="closeModal('modalView-{{ $historial->historial_id }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-grid">
                                    <p><strong>ID Registro:</strong> {{ $historial->historial_id }}</p>
                                <p><strong>Persona Implicada:</strong> {{ $historial->persona->Nombre ?? 'N/A' }} {{ $historial->persona->Paterno ?? '' }} ({{ $historial->persona_id }})</p>
                                <p><strong>Delito:</strong> {{ $historial->delito->Nombre ?? 'N/A' }} ({{ $historial->delito->cargo_penal ?? '' }})</p>
                                <p><strong>Fecha del Delito:</strong> {{ $historial->fecha_delito }}</p>
                                <p><strong>Estatus:</strong> {{ ucfirst(str_replace('_', ' ', $historial->estatus)) }}</p>
                                    <p><strong>Descripción / Observaciones:</strong></p>
                                    <p style="background: rgba(15,23,42,0.6); padding: 1rem; border-radius: 0.5rem;">{{ $historial->descripcion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                    <div id="modalEdit-{{ $historial->historial_id }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 800px;">
                            <div class="modal-header">
                                <h3><i class="fa-solid fa-pen"></i> Editar Historial</h3>
                                <button class="btn-close" onclick="closeModal('modalEdit-{{ $historial->historial_id }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form action="{{ route('historial.update', $historial->historial_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-grid">
                                        <div class="form-group">
                                        <label>Persona Implicada *</label>
                                        <select name="persona_id" class="form-control" required>
                                            @foreach($personas as $persona)
                                            <option value="{{ $persona->nun_documento }}" {{ $historial->persona_id == $persona->nun_documento ? 'selected' : '' }}>{{ $persona->nun_documento }} - {{ $persona->Nombre }} {{ $persona->Paterno }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Delito *</label>
                                        <select name="delito_id" class="form-control" required>
                                            @foreach($delitos as $delito)
                                            <option value="{{ $delito->delito_id }}" {{ $historial->delito_id == $delito->delito_id ? 'selected' : '' }}>{{ $delito->Nombre }} ({{ $delito->cargo_penal }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha del Delito *</label>
                                        <input type="date" name="fecha_delito" class="form-control" value="{{ $historial->fecha_delito }}" max="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción / Observaciones *</label>
                                        <textarea name="descripcion" class="form-control" rows="3" required>{{ $historial->descripcion }}</textarea>
                                    </div>
                                        <div class="form-group">
                                            <label>Estatus *</label>
                                            <select name="estatus" class="form-control" required>
                                                <option value="cerrado" {{ $historial->estatus == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                                                <option value="bajo presentacion" {{ $historial->estatus == 'bajo presentacion' ? 'selected' : '' }}>Bajo presentación</option>
                                                <option value="orden de captura" {{ $historial->estatus == 'orden de captura' ? 'selected' : '' }}>Orden de captura</option>
                                                <option value="en investigacion" {{ $historial->estatus == 'en investigacion' ? 'selected' : '' }}>En investigación</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEdit-{{ $historial->historial_id }}')">Cancelar</button>
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
            <div style="margin-top: 1rem;">{{ $historiales->links() }}</div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="modalCreate" class="modal-overlay">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3>Nuevo Historial</h3>
                <button class="btn-close" onclick="closeModal('modalCreate')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('historial.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                        <label>Persona Implicada *</label>
                        <select name="persona_id" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($personas as $persona)
                            <option value="{{ $persona->nun_documento }}">{{ $persona->nun_documento }} - {{ $persona->Nombre }} {{ $persona->Paterno }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Delito *</label>
                        <select name="delito_id" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($delitos as $delito)
                            <option value="{{ $delito->delito_id }}">{{ $delito->Nombre }} ({{ $delito->cargo_penal }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha del Delito *</label>
                        <input type="date" name="fecha_delito" class="form-control" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción / Observaciones *</label>
                        <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                        <div class="form-group">
                            <label>Estatus *</label>
                            <select name="estatus" class="form-control" required>
                                <option value="cerrado">Cerrado</option>
                                <option value="bajo presentacion">Bajo presentación</option>
                                <option value="orden de captura">Orden de captura</option>
                                <option value="en investigacion">En investigación</option>
                            </select>
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
