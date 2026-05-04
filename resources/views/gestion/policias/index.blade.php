@extends('layouts.app')

@section('title', 'Gestión de Policías')

@section('styles')
<style>
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.875rem; }
    .form-control { width: 100%; padding: 0.75rem; background: rgba(15, 23, 42, 0.5); border: 1px solid var(--border-color); border-radius: 0.5rem; color: var(--text-main); transition: border-color 0.3s; }
    .form-control:focus { outline: none; border-color: #3b82f6; }
    .input-group { display: flex; }
    .input-group select { width: 80px; border-top-right-radius: 0; border-bottom-right-radius: 0; border-right: none; }
    .input-group input { flex-grow: 1; border-top-left-radius: 0; border-bottom-left-radius: 0; }
</style>
@endsection

@section('content')
    <div class="header-actions" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="page-title" style="margin-bottom: 0;"><i class="fa-solid fa-user-shield"></i> Gestión de Policías</h1>
        <button class="btn btn-primary" onclick="openModal('modalPolicia')">
            <i class="fa-solid fa-plus"></i> Registrar Policía
        </button>
    </div>

    @if($errors->any())
        <div style="background: rgba(239,68,68,0.2); border:1px solid #ef4444; color:#f87171; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
            <i class="fa-solid fa-triangle-exclamation"></i> <strong>Por favor corrija los siguientes errores:</strong>
            <ul style="margin-top:0.5rem; padding-left:1.25rem;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="table-container" style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                        <th style="padding: 1rem;">ID</th>
                        <th style="padding: 1rem;">Foto</th>
                        <th style="padding: 1rem;">Documento</th>
                        <th style="padding: 1rem;">Nombre</th>
                        <th style="padding: 1rem;">Usuario</th>
                        <th style="padding: 1rem;">Rol</th>
                        <th style="padding: 1rem;">Grado / Esp.</th>
                        <th style="padding: 1rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($policias as $pol)
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.5);">
                            <td style="padding: 1rem;">{{ $pol->Policia_id }}</td>
                            <td style="padding: 1rem;">
                                @if($pol->usuarioObj && $pol->usuarioObj->Foto)
                                    <img src="{{ asset($pol->usuarioObj->Foto) }}" alt="Foto" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--border-color); display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 1rem;">{{ $pol->nun_documento }}</td>
                            <td style="padding: 1rem;">{{ $pol->nombre }} {{ $pol->apellido }}</td>
                            <td style="padding: 1rem;">{{ $pol->usuarioObj->usuario ?? 'N/A' }}</td>
                            <td style="padding: 1rem; text-transform: capitalize;">{{ $pol->usuarioObj->rolObj->nombre_rol ?? 'N/A' }}</td>
                            <td style="padding: 1rem;">{{ $pol->Grado }} <br><small style="color: var(--text-muted)">{{ $pol->especialidad }}</small></td>
                            <td style="padding: 1rem;">
                                <button class="btn btn-primary" style="padding: 0.25rem 0.5rem;" onclick="openEditPolicia(
                                    {{ $pol->Policia_id }}, 
                                    '{{ addslashes($pol->nun_documento) }}', 
                                    '{{ addslashes($pol->nombre ?? '') }}',
                                    '{{ addslashes($pol->apellido ?? '') }}',
                                    '{{ $pol->sexo ?? '' }}',
                                    '{{ $pol->fecha_nac ?? '' }}',
                                    '{{ addslashes($pol->telefono ?? '') }}',
                                    '{{ addslashes($pol->especialidad) }}', 
                                    '{{ addslashes($pol->Grado) }}',
                                    '{{ addslashes($pol->usuarioObj->usuario ?? '') }}',
                                    {{ $pol->usuarioObj->rol ?? 'null' }}
                                )">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                
                                <form action="{{ route('policias.destroy', $pol->Policia_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar a este policía y su usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 0.25rem 0.5rem;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">{{ $policias->links() }}</div>
    </div>

    <!-- Modal Policía -->
    <div id="modalPolicia" class="modal-overlay">
        <div class="modal-content" style="max-width: 950px;">
            <div class="modal-header">
                <h3 id="modalPoliciaTitle">Registrar Policía</h3>
                <button type="button" class="btn-close" onclick="closeModal('modalPolicia')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formPolicia" method="POST" action="{{ route('policias.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="policiaMethod" value="POST">
                    
                    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                        <!-- Datos Personales -->
                        <div style="flex: 1; min-width: 250px;">
                            <h4 style="margin-bottom: 1rem; color: var(--primary);">Datos Personales</h4>
                            <div class="form-group">
                                <label>Documento de Identidad *</label>
                                <div class="input-group" id="policiaDocGroup">
                                    <select name="doc_type" id="policiaDocType" class="form-control" required>
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                        <option value="P">P</option>
                                    </select>
                                    <input type="number" name="doc_number" id="policiaDocNumber" class="form-control" placeholder="12345678" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nombre *</label>
                                <input type="text" name="nombre" id="policiaNombre" class="form-control" maxlength="30" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido *</label>
                                <input type="text" name="apellido" id="policiaApellido" class="form-control" maxlength="30" required>
                            </div>
                            <div class="form-group">
                                <label>Sexo *</label>
                                <select name="sexo" id="policiaSexo" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nac" id="policiaFechaNac" class="form-control" max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" id="policiaTelefono" class="form-control" maxlength="15">
                            </div>
                        </div>

                        <!-- Datos Policiales -->
                        <div style="flex: 1; min-width: 250px;">
                            <h4 style="margin-bottom: 1rem; color: var(--primary);">Datos Policiales</h4>
                            <div class="form-group">
                                <label>Grado *</label>
                                <select name="Grado" id="policiaGrado" class="form-control" required>
                                    <option value="">Seleccione un Grado</option>
                                    <option value="Oficial">Oficial</option>
                                    <option value="Primer Oficial">Primer Oficial</option>
                                    <option value="Coronel">Coronel</option>
                                    <option value="Sargento">Sargento</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Especialidad *</label>
                                <select name="especialidad" id="policiaEspecialidad" class="form-control" required>
                                    <option value="">Seleccione una Especialidad</option>
                                    <option value="Policia Nacional">Policía Nacional</option>
                                    <option value="Policia Estatal">Policía Estatal</option>
                                    <option value="Bomberos">Bomberos</option>
                                    <option value="Proteccion Civil">Protección Civil</option>
                                </select>
                            </div>
                        </div>

                        <!-- Datos de Usuario -->
                        <div style="flex: 1; min-width: 250px;">
                            <h4 style="margin-bottom: 1rem; color: var(--primary);">Datos de Usuario</h4>
                            <div class="form-group">
                                <label>Nombre de Usuario *</label>
                                <input type="text" name="usuario" id="policiaUsuario" class="form-control" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="Contrasena" id="policiaContrasena" class="form-control" placeholder="Mínimo 6 caracteres" required>
                                <small style="color: var(--text-muted); font-size: 0.75rem;">Al editar, déjela en blanco para no cambiarla.</small>
                            </div>
                            <div class="form-group">
                                <label>Rol de Acceso *</label>
                                <select name="rol" id="policiaRol" class="form-control" required>
                                    <option value="">Seleccione un Rol</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->rol_id }}">{{ ucfirst($rol->nombre_rol) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Foto de Perfil</label>
                                <input type="file" name="foto" id="policiaFoto" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="margin-top: 1rem; padding-bottom: 0;">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('modalPolicia')">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Registro</button>
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
        
        if(id === 'modalPolicia') {
            document.getElementById('formPolicia').reset();
            document.getElementById('formPolicia').action = "{{ route('policias.store') }}";
            document.getElementById('policiaMethod').value = 'POST';
            document.getElementById('modalPoliciaTitle').innerText = 'Registrar Policía';
            document.getElementById('policiaContrasena').required = true;

            // Desbloquear documento al volver a modo registro
            document.getElementById('policiaDocType').disabled = false;
            document.getElementById('policiaDocNumber').disabled = false;
            document.getElementById('policiaDocGroup').style.opacity = '1';
        }
    }

    function openEditPolicia(id, documento, nombre, apellido, sexo, fechaNac, telefono, especialidad, grado, usuario, rol_id) {
        document.getElementById('modalPoliciaTitle').innerText = 'Editar Policía';
        document.getElementById('formPolicia').action = `/policias/${id}`;
        document.getElementById('policiaMethod').value = 'PUT';
        
        // Documento: mostrar pero NO permitir editar
        if (documento && documento.includes('-')) {
            var parts = documento.split('-');
            document.getElementById('policiaDocType').value = parts[0];
            document.getElementById('policiaDocNumber').value = parts[1];
        } else {
            document.getElementById('policiaDocNumber').value = documento;
        }
        document.getElementById('policiaDocType').disabled = true;
        document.getElementById('policiaDocNumber').disabled = true;
        document.getElementById('policiaDocGroup').style.opacity = '0.5';

        // Datos personales
        document.getElementById('policiaNombre').value = nombre;
        document.getElementById('policiaApellido').value = apellido;
        document.getElementById('policiaSexo').value = sexo;
        document.getElementById('policiaFechaNac').value = fechaNac;
        document.getElementById('policiaTelefono').value = telefono;

        // Datos policiales
        document.getElementById('policiaEspecialidad').value = especialidad;
        document.getElementById('policiaGrado').value = grado;

        // Datos de usuario
        document.getElementById('policiaUsuario').value = usuario;
        if (rol_id) {
            document.getElementById('policiaRol').value = rol_id;
        }
        
        document.getElementById('policiaContrasena').required = false;

        openModal('modalPolicia');
    }
</script>
@endsection
