@extends('layouts.app')

@section('title', 'Catálogo de Delitos')

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
        <div><i class="fa-solid fa-gavel"></i> Catálogo de Delitos</div>
        <button class="btn btn-primary" onclick="openModal('modalCreate')">
            <i class="fa-solid fa-plus"></i> Registrar Delito
        </button>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Delito</th>
                        <th>Tipo</th>
                        <th>Cargo Penal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($delitos as $delito)
                    <tr>
                        <td>{{ $delito->delito_id }}</td>
                        <td>{{ $delito->Nombre }}</td>
                        <td>{{ ucfirst($delito->Tipo) }}</td>
                        <td>{{ $delito->cargo_penal }}</td>
                        <td class="action-btns">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Ver Detalle" onclick="openModal('modalView-{{ $delito->delito_id }}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Editar" onclick="openModal('modalEdit-{{ $delito->delito_id }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('delitos.destroy', $delito->delito_id) }}" method="POST" style="display:inline;">
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
                    <div id="modalView-{{ $delito->delito_id }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 600px;">
                            <div class="modal-header">
                                <h3>Detalle del Delito</h3>
                                <button class="btn-close" onclick="closeModal('modalView-{{ $delito->delito_id }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-grid">
                                    <p><strong>ID:</strong> {{ $delito->delito_id }}</p>
                                <p><strong>Nombre:</strong> {{ $delito->Nombre }}</p>
                                <p><strong>Tipo:</strong> {{ ucfirst($delito->Tipo) }}</p>
                                    <p><strong>Cargo Penal:</strong> {{ $delito->cargo_penal }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                    <div id="modalEdit-{{ $delito->delito_id }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 600px;">
                            <div class="modal-header">
                                <h3>Editar Delito</h3>
                                <button class="btn-close" onclick="closeModal('modalEdit-{{ $delito->delito_id }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form action="{{ route('delitos.update', $delito->delito_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-grid">
                                        <div class="form-group">
                                        <label>Nombre del Delito *</label>
                                        <input type="text" name="Nombre" class="form-control" value="{{ $delito->Nombre }}" maxlength="100" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipo *</label>
                                        <select name="Tipo" class="form-control" required>
                                            <option value="penal" {{ $delito->Tipo == 'penal' ? 'selected' : '' }}>Penal</option>
                                            <option value="faltas" {{ $delito->Tipo == 'faltas' ? 'selected' : '' }}>Faltas</option>
                                            <option value="medida cautelar" {{ $delito->Tipo == 'medida cautelar' ? 'selected' : '' }}>Medida Cautelar</option>
                                            <option value="infraccion" {{ $delito->Tipo == 'infraccion' ? 'selected' : '' }}>Infracción</option>
                                        </select>
                                    </div>
                                        <div class="form-group">
                                            <label>Cargo Penal *</label>
                                            <input type="text" name="cargo_penal" class="form-control" value="{{ $delito->cargo_penal }}" maxlength="100" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEdit-{{ $delito->delito_id }}')">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">Sin registros.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $delitos->links() }}</div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="modalCreate" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Nuevo Delito</h3>
                <button class="btn-close" onclick="closeModal('modalCreate')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('delitos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                        <label>Nombre del Delito *</label>
                        <input type="text" name="Nombre" class="form-control" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo *</label>
                        <select name="Tipo" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="penal">Penal</option>
                            <option value="faltas">Faltas</option>
                            <option value="medida cautelar">Medida Cautelar</option>
                            <option value="infraccion">Infracción</option>
                        </select>
                    </div>
                        <div class="form-group">
                            <label>Cargo Penal *</label>
                            <input type="text" name="cargo_penal" class="form-control" maxlength="100" required>
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
