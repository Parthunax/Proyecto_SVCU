@extends('layouts.app')

@section('title', 'Catálogo de Marcas')

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
        <div><i class="fa-solid fa-tags"></i> Catálogo de Marcas</div>
        <button class="btn btn-primary" onclick="openModal('modalCreate')">
            <i class="fa-solid fa-plus"></i> Registrar Marca
        </button>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Marca</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marcas as $marca)
                    <tr>
                        <td>{{ $marca->marca_id }}</td>
                        <td>{{ $marca->nombre_marca }}</td>
                        <td>{{ Str::limit($marca->descripcion, 50) }}</td>
                        <td class="action-btns">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Ver Detalle" onclick="openModal('modalView-{{ $marca->marca_id }}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="Editar" onclick="openModal('modalEdit-{{ $marca->marca_id }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('marcas.destroy', $marca->marca_id) }}" method="POST" style="display:inline;">
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
                    <div id="modalView-{{ $marca->marca_id }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 600px;">
                            <div class="modal-header">
                                <h3><i class="fa-solid fa-tags"></i> Detalle de Marca</h3>
                                <button class="btn-close" onclick="closeModal('modalView-{{ $marca->marca_id }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-grid">
                                    <p><strong>ID:</strong> {{ $marca->marca_id }}</p>
                                <p><strong>Nombre de Marca:</strong> {{ $marca->nombre_marca }}</p>
                                    <p><strong>Descripción:</strong></p>
                                    <p style="background: rgba(15,23,42,0.6); padding: 1rem; border-radius: 0.5rem;">{{ $marca->descripcion ?? 'Sin descripción' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                    <div id="modalEdit-{{ $marca->marca_id }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 600px;">
                            <div class="modal-header">
                                <h3><i class="fa-solid fa-pen"></i> Editar Marca</h3>
                                <button class="btn-close" onclick="closeModal('modalEdit-{{ $marca->marca_id }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <form action="{{ route('marcas.update', $marca->marca_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-grid">
                                        <div class="form-group">
                                        <label>Nombre de la Marca *</label>
                                        <input type="text" name="nombre_marca" class="form-control" value="{{ $marca->nombre_marca }}" maxlength="100" required>
                                    </div>
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea name="descripcion" class="form-control" rows="3">{{ $marca->descripcion }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEdit-{{ $marca->marca_id }}')">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-muted);">Sin registros.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $marcas->links() }}</div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div id="modalCreate" class="modal-overlay">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Nueva Marca</h3>
                <button class="btn-close" onclick="closeModal('modalCreate')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('marcas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                        <label>Nombre de la Marca *</label>
                        <input type="text" name="nombre_marca" class="form-control" maxlength="100" required>
                    </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
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
