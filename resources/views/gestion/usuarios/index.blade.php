@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('styles')
<style>
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
    .form-control { width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.5); border: 1px solid var(--border-color); border-radius: 0.5rem; color: var(--text-main); transition: border-color 0.3s; }
    .form-control:focus { outline: none; border-color: #3b82f6; }
    .badge { padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; }
    .badge-activo { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid #22c55e; }
    .badge-inactivo { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; }
</style>
@endsection

@section('content')
    <div class="header-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="page-title" style="margin-bottom: 0;"><i class="fa-solid fa-users"></i> Gestión de Cuentas de Usuario</h1>
    </div>

    <div class="card">
        <div class="table-container" style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Usuario</th>
                        <th style="padding: 1rem;">Documento</th>
                        <th style="padding: 1rem;">Rol</th>
                        <th style="padding: 1rem;">Estatus</th>
                        <th style="padding: 1rem;">Último Acceso</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.5);">
                            <td style="padding: 1rem;">{{ $user->usuario_id }}</td>
                            <td style="padding: 1rem; font-weight: bold;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    @if($user->Foto)
                                        <img src="{{ asset($user->Foto) }}" alt="Foto" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                            {{ strtoupper(substr($user->usuario, 0, 1)) }}
                                        </div>
                                    @endif
                                    {{ $user->usuario }}
                                </div>
                            </td>
                            <td style="padding: 1rem;">{{ $user->nun_documento }}</td>
                            <td style="padding: 1rem; text-transform: capitalize;">{{ $user->rolObj->nombre_rol ?? 'N/A' }}</td>
                            <td style="padding: 1rem;">
                                <span class="badge {{ $user->estadus === 'activo' ? 'badge-activo' : 'badge-inactivo' }}">
                                    {{ strtoupper($user->estadus) }}
                                </span>
                            </td>
                            <td style="padding: 1rem; color: var(--text-muted); font-size: 0.875rem;">
                                {{ $user->ultimo_acceso ? \Carbon\Carbon::parse($user->ultimo_acceso)->format('d/m/Y h:i A') : 'Nunca' }}
                            </td>
                            <td style="padding: 1rem;">
                                <button class="btn btn-primary" style="padding: 0.25rem 0.5rem;" onclick="openEditUsuario(
                                    {{ $user->usuario_id }}, 
                                    '{{ addslashes($user->usuario) }}',
                                    {{ $user->rol }},
                                    '{{ $user->estadus }}'
                                )">
                                    <i class="fa-solid fa-pen"></i> Ajustar Permisos
                                </button>
                                
                                <form action="{{ route('usuarios.destroy', $user->usuario_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 0.25rem 0.5rem;" title="Eliminar Usuario">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">{{ $usuarios->links() }}</div>
    </div>

    <!-- Modal Usuario -->
    <div id="modalUsuario" class="modal-overlay">
        <div class="modal-content" style="max-width: 500px; height: auto;">
            <div class="modal-header">
                <h3>Ajustar Permisos de Usuario</h3>
                <button type="button" class="btn-close" onclick="closeModal('modalUsuario')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formUsuario" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" id="displayUsuario" class="form-control" disabled style="background: rgba(0,0,0,0.2);">
                    </div>
                    
                    <div class="form-group">
                        <label>Rol de Acceso</label>
                        <select name="rol" id="usuarioRol" class="form-control" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->rol_id }}">{{ ucfirst($rol->nombre_rol) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Estatus de la Cuenta</label>
                        <select name="estadus" id="usuarioEstatus" class="form-control" required>
                            <option value="activo">Activo (Puede Iniciar Sesión)</option>
                            <option value="inactivo">Inactivo (Suspendido)</option>
                        </select>
                        <small style="color: var(--text-muted); font-size: 0.75rem;">Un usuario inactivo no podrá acceder al sistema.</small>
                    </div>

                    <div class="modal-footer" style="margin-top: 1rem; padding-bottom: 0;">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('modalUsuario')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function openEditUsuario(id, username, rol_id, estatus) {
        document.getElementById('formUsuario').action = `/usuarios/${id}`;
        document.getElementById('displayUsuario').value = username;
        document.getElementById('usuarioRol').value = rol_id;
        document.getElementById('usuarioEstatus').value = estatus;

        openModal('modalUsuario');
    }
</script>
@endsection
