@extends('layouts.app')

@section('title', 'Gestión de Personas')

@section('styles')
<style>
    /* Tabs Styles */
    .tab-header { display: flex; border-bottom: 1px solid var(--border-color); margin-bottom: 1.5rem; }
    .tab-btn { padding: 1rem 2rem; background: none; border: none; color: var(--text-muted); font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
    .tab-content { display: none; animation: fadeIn 0.3s; }
    .tab-content.active { display: block; }
    
    /* Form Styles */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 500; color: var(--text-muted); }
    .form-control { width: 100%; padding: 0.75rem 1rem; background-color: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-color); border-radius: 0.5rem; color: var(--text-main); font-size: 1rem; transition: all 0.2s; }
    .form-control:focus { outline: none; border-color: var(--primary); }
    .form-control:disabled { opacity: 0.5; cursor: not-allowed; }
    
    /* Input Group */
    .input-group { display: flex; }
    .input-group select { width: 80px; border-top-right-radius: 0; border-bottom-right-radius: 0; border-right: none; }
    .input-group input { flex-grow: 1; border-top-left-radius: 0; border-bottom-left-radius: 0; }
    
    /* Image Preview */
    .image-upload-wrapper { display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem; }
    .image-preview { width: 100px; height: 100px; border-radius: 0.5rem; border: 1px dashed var(--border-color); display: flex; align-items: center; justify-content: center; overflow: hidden; background-color: rgba(15, 23, 42, 0.6); }
    .image-preview img { width: 100%; height: 100%; object-fit: cover; display: none; }
    
    /* Table Styles */
    .table-container { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; text-align: left; }
    .data-table th { padding: 1rem; border-bottom: 2px solid var(--border-color); color: var(--text-muted); font-weight: 600; font-size: 0.875rem; text-transform: uppercase; }
    .data-table td { padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.5); color: var(--text-main); vertical-align: middle; }
    .action-btns { display: flex; gap: 5px; }
</style>
@endsection

@section('content')
    <div class="page-title" style="display: flex; justify-content: space-between; align-items: center;">
        <div><i class="fa-solid fa-users-gear"></i> Gestión de Personas</div>
        <button class="btn btn-primary" onclick="openModal('modalCreate')">
            <i class="fa-solid fa-plus"></i> Registrar Persona
        </button>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personas as $persona)
                    <tr>
                        <td>{{ $persona->nun_documento }}</td>
                        <td>{{ $persona->Nombre }}</td>
                        <td>{{ $persona->Paterno }} {{ $persona->Materno }}</td>
                        <td>{{ $persona->Genero }}</td>
                        <td>{{ $persona->Telefono }}</td>
                        <td class="action-btns">
                            <button class="btn btn-secondary" style="padding: 0.5rem; font-size: 0.8rem;" title="Ver Detalle" onclick="openModal('modalView-{{ $persona->nun_documento }}')">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                            <button class="btn btn-secondary" style="padding: 0.5rem; font-size: 0.8rem;" title="Editar" onclick="openModal('modalEdit-{{ $persona->nun_documento }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('personas.destroy', $persona->nun_documento) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 0.5rem; font-size: 0.8rem; color: #ef4444;" title="Eliminar" onclick="return confirm('¿Seguro que desea eliminar este registro?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Ver Detalles -->
                    <div id="modalView-{{ $persona->nun_documento }}" class="modal-overlay">
                        <div class="modal-content" style="max-height: 80vh; overflow-y: auto;">
                            <div class="modal-header">
                                <h3><i class="fa-solid fa-address-card"></i> Detalles de la Persona</h3>
                                <button class="btn-close" onclick="closeModal('modalView-{{ $persona->nun_documento }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            <div class="modal-body">
                                <h4>Datos Personales</h4>
                                <hr style="border-color: var(--border-color); margin: 0.5rem 0 1rem;">
                                <div class="form-grid">
                                    <p><strong>Documento:</strong> {{ $persona->nun_documento }}</p>
                                    <p><strong>Nombres:</strong> {{ $persona->Nombre }}</p>
                                    <p><strong>Apellidos:</strong> {{ $persona->Paterno }} {{ $persona->Materno }}</p>
                                    <p><strong>Género:</strong> {{ $persona->Genero == 'M' ? 'Masculino' : 'Femenino' }}</p>
                                    <p><strong>Estado Civil:</strong> {{ ucfirst($persona->EstadoCivil) }}</p>
                                    <p><strong>Nacimiento:</strong> {{ $persona->FechaNacimiento }}</p>
                                    <p><strong>Teléfono:</strong> {{ $persona->Telefono }}</p>
                                </div>
                                <br>
                                <h4>Dirección</h4>
                                <hr style="border-color: var(--border-color); margin: 0.5rem 0 1rem;">
                                <div class="form-grid">
                                    <p><strong>Parroquia ID:</strong> {{ $persona->direccion->parroquia_id ?? 'N/A' }}</p>
                                    <p><strong>Localidad:</strong> {{ $persona->direccion->localidad ?? 'N/A' }}</p>
                                    <p><strong>Ruta:</strong> {{ $persona->direccion->ruta ?? 'N/A' }}</p>
                                    <p><strong>Vivienda:</strong> {{ $persona->direccion->tipo_vivienda ?? 'N/A' }} ({{ $persona->direccion->nun_vivienda ?? 'S/N' }})</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Editar -->
                    @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                    <div id="modalEdit-{{ $persona->nun_documento }}" class="modal-overlay">
                        <div class="modal-content" style="max-width: 900px;">
                            <div class="modal-header">
                                <h3><i class="fa-solid fa-user-pen"></i> Editar Registro de Persona</h3>
                                <button class="btn-close" onclick="closeModal('modalEdit-{{ $persona->nun_documento }}')"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                            
                            <form action="{{ route('personas.update', $persona->nun_documento) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex-grow: 1;">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="tab-header">
                                        <button type="button" class="tab-btn active" onclick="switchTab(this, 'edit-tab-datos-{{ $persona->nun_documento }}', '{{ $persona->nun_documento }}')">1. Datos Personales</button>
                                        <button type="button" class="tab-btn" onclick="switchTab(this, 'edit-tab-direccion-{{ $persona->nun_documento }}', '{{ $persona->nun_documento }}')">2. Dirección</button>
                                    </div>

                                    <!-- Pestaña 1: Datos Personales (Editar) -->
                                    <div id="edit-tab-datos-{{ $persona->nun_documento }}" class="tab-content active tab-group-{{ $persona->nun_documento }}">
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label>Número de Documento (No editable)</label>
                                                <input type="text" class="form-control" value="{{ $persona->nun_documento }}" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombres *</label>
                                                <input type="text" name="Nombre" class="form-control" value="{{ $persona->Nombre }}" maxlength="100" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Apellido Paterno *</label>
                                                <input type="text" name="Paterno" class="form-control" value="{{ $persona->Paterno }}" maxlength="100" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Apellido Materno</label>
                                                <input type="text" name="Materno" class="form-control" value="{{ $persona->Materno }}" maxlength="100">
                                            </div>
                                            <div class="form-group">
                                                <label>Género *</label>
                                                <select name="Genero" class="form-control" required>
                                                    <option value="M" {{ $persona->Genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                                    <option value="F" {{ $persona->Genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Estado Civil *</label>
                                                <select name="EstadoCivil" class="form-control" required>
                                                    <option value="soltero/a" {{ $persona->EstadoCivil == 'soltero/a' ? 'selected' : '' }}>Soltero/a</option>
                                                    <option value="casado/a" {{ $persona->EstadoCivil == 'casado/a' ? 'selected' : '' }}>Casado/a</option>
                                                    <option value="divorciado/a" {{ $persona->EstadoCivil == 'divorciado/a' ? 'selected' : '' }}>Divorciado/a</option>
                                                    <option value="viudo/a" {{ $persona->EstadoCivil == 'viudo/a' ? 'selected' : '' }}>Viudo/a</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha de Nacimiento *</label>
                                                <input type="date" name="FechaNacimiento" class="form-control" value="{{ $persona->FechaNacimiento }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="text" name="Telefono" class="form-control" value="{{ $persona->Telefono }}" maxlength="20">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pestaña 2: Dirección (Editar) -->
                                    <div id="edit-tab-direccion-{{ $persona->nun_documento }}" class="tab-content tab-group-{{ $persona->nun_documento }}">
                                        <div class="form-grid">
                                            <div class="form-group">
                                                <label>Estado *</label>
                                                <select class="form-control" onchange="loadMunicipios(this.value, '-{{ $persona->nun_documento }}')">
                                                    <option value="">Seleccione Estado...</option>
                                                    @foreach($estados as $estado)
                                                        <option value="{{ $estado->estado_id }}" {{ ($persona->direccion->parroquia->municipioObj->estado_id ?? '') == $estado->estado_id ? 'selected' : '' }}>{{ $estado->estado }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Municipio *</label>
                                                <select id="select-municipio-{{ $persona->nun_documento }}" class="form-control" onchange="loadParroquias(this.value, '-{{ $persona->nun_documento }}')">
                                                    @if(isset($persona->direccion->parroquia->municipioObj))
                                                        <option value="{{ $persona->direccion->parroquia->municipio_id }}">{{ $persona->direccion->parroquia->municipioObj->municipio }}</option>
                                                    @else
                                                        <option value="">Seleccione primero el Estado...</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Parroquia *</label>
                                                <select name="parroquia_id" id="select-parroquia-{{ $persona->nun_documento }}" class="form-control" required>
                                                    @if(isset($persona->direccion->parroquia))
                                                        <option value="{{ $persona->direccion->parroquia_id }}">{{ $persona->direccion->parroquia->parroquia }}</option>
                                                    @else
                                                        <option value="">Seleccione primero el Municipio...</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Localidad / Sector *</label>
                                                <input type="text" name="localidad" class="form-control" value="{{ $persona->direccion->localidad ?? '' }}" maxlength="200" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Tipo de Vivienda *</label>
                                                <select name="tipo_vivienda" class="form-control" required>
                                                    <option value="casa" {{ ($persona->direccion->tipo_vivienda ?? '') == 'casa' ? 'selected' : '' }}>Casa</option>
                                                    <option value="apartamento" {{ ($persona->direccion->tipo_vivienda ?? '') == 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                                                    <option value="rancho" {{ ($persona->direccion->tipo_vivienda ?? '') == 'rancho' ? 'selected' : '' }}>Rancho</option>
                                                    <option value="quinta" {{ ($persona->direccion->tipo_vivienda ?? '') == 'quinta' ? 'selected' : '' }}>Quinta</option>
                                                    <option value="edificio" {{ ($persona->direccion->tipo_vivienda ?? '') == 'edificio' ? 'selected' : '' }}>Edificio</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Ruta (Calle/Avenida)</label>
                                                <input type="text" name="ruta" class="form-control" value="{{ $persona->direccion->ruta ?? '' }}" maxlength="100">
                                            </div>
                                            <div class="form-group">
                                                <label>Número de Vivienda</label>
                                                <input type="number" name="nun_vivienda" class="form-control" value="{{ $persona->direccion->nun_vivienda ?? '' }}" maxlength="5" min="0" max="99999" oninput="if(this.value.length>5)this.value=this.value.slice(0,5)">
                                            </div>
                                        </div>
                                        
                                        <div style="margin-top: 2rem; text-align: right; display: flex; justify-content: flex-end;">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa-solid fa-floppy-disk"></i> Actualizar Persona
                                            </button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No hay personas registradas aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div style="margin-top: 1rem;">
                {{ $personas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de Creación (Con AJAX) -->
    <div id="modalCreate" class="modal-overlay">
        <div class="modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h3><i class="fa-solid fa-user-plus"></i> Nuevo Registro de Persona</h3>
                <button class="btn-close" onclick="closeModal('modalCreate')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="{{ route('personas.store') }}" method="POST" enctype="multipart/form-data" id="formPersona" style="display: flex; flex-direction: column; flex-grow: 1;">
                @csrf
                <div class="modal-body">
                    
                    <div class="tab-header">
                        <button type="button" class="tab-btn active" onclick="switchTab(this, 'tab-datos', 'create')">1. Datos Personales</button>
                        <button type="button" class="tab-btn" onclick="switchTab(this, 'tab-direccion', 'create')">2. Dirección / Residencia</button>
                    </div>

                    <!-- Pestaña 1: Datos Personales -->
                    <div id="tab-datos" class="tab-content active tab-group-create">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Documento de Identidad *</label>
                                <div class="input-group">
                                    <select name="doc_type" class="form-control" required>
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                        <option value="P">P</option>
                                    </select>
                                    <input type="number" name="doc_number" class="form-control" placeholder="12345678" oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nombres *</label>
                                <input type="text" name="Nombre" class="form-control" maxlength="100" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido Paterno *</label>
                                <input type="text" name="Paterno" class="form-control" maxlength="100" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido Materno</label>
                                <input type="text" name="Materno" class="form-control" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label>Género *</label>
                                <select name="Genero" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Estado Civil *</label>
                                <select name="EstadoCivil" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="soltero/a">Soltero/a</option>
                                    <option value="casado/a">Casado/a</option>
                                    <option value="divorciado/a">Divorciado/a</option>
                                    <option value="viudo/a">Viudo/a</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fecha de Nacimiento *</label>
                                <input type="date" name="FechaNacimiento" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="Telefono" class="form-control" maxlength="20">
                            </div>
                        </div>

                        <div class="form-grid" style="margin-top: 1.5rem;">
                            <div class="form-group">
                                <label>Foto del Rostro</label>
                                <div class="image-upload-wrapper">
                                    <div class="image-preview" id="previewCara">
                                        <i class="fa-solid fa-camera" style="color: var(--text-muted); font-size: 2rem;"></i>
                                        <img src="" alt="Rostro">
                                    </div>
                                    <input type="file" name="foto_cara" class="form-control" accept="image/*" onchange="previewImage(this, 'previewCara')">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Foto de Huella Dactilar</label>
                                <div class="image-upload-wrapper">
                                    <div class="image-preview" id="previewHuella">
                                        <i class="fa-solid fa-fingerprint" style="color: var(--text-muted); font-size: 2rem;"></i>
                                        <img src="" alt="Huella">
                                    </div>
                                    <input type="file" name="foto_huella" class="form-control" accept="image/*" onchange="previewImage(this, 'previewHuella')">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 2rem; text-align: right;">
                            <button type="button" class="btn btn-primary" onclick="switchTab(document.querySelector('#modalCreate .tab-btn:nth-child(2)'), 'tab-direccion', 'create')">
                                Siguiente <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Pestaña 2: Dirección -->
                    <div id="tab-direccion" class="tab-content tab-group-create">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Estado *</label>
                                <select id="select-estado" class="form-control" required onchange="loadMunicipios(this.value)">
                                    <option value="">Seleccione Estado...</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->estado_id }}">{{ $estado->estado }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Municipio *</label>
                                <select id="select-municipio" class="form-control" required disabled onchange="loadParroquias(this.value)">
                                    <option value="">Seleccione primero el Estado...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Parroquia *</label>
                                <select name="parroquia_id" id="select-parroquia" class="form-control" required disabled>
                                    <option value="">Seleccione primero el Municipio...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Localidad / Sector *</label>
                                <input type="text" name="localidad" class="form-control" maxlength="200" required>
                            </div>
                            <div class="form-group">
                                <label>Tipo de Vivienda *</label>
                                <select name="tipo_vivienda" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="casa">Casa</option>
                                    <option value="apartamento">Apartamento</option>
                                    <option value="rancho">Rancho</option>
                                    <option value="quinta">Quinta</option>
                                    <option value="edificio">Edificio</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Ruta (Calle/Avenida)</label>
                                <input type="text" name="ruta" class="form-control" maxlength="100">
                            </div>
                            <div class="form-group">
                                <label>Número de Vivienda</label>
                                <input type="number" name="nun_vivienda" class="form-control" maxlength="5" min="0" max="99999" oninput="if(this.value.length>5)this.value=this.value.slice(0,5)">
                            </div>
                        </div>
                        
                        <div style="margin-top: 2rem; text-align: right; display: flex; justify-content: space-between;">
                            <button type="button" class="btn btn-secondary" onclick="switchTab(document.querySelector('#modalCreate .tab-btn:nth-child(1)'), 'tab-datos', 'create')">
                                <i class="fa-solid fa-arrow-left"></i> Anterior
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Registro Completo
                            </button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    function switchTab(buttonElement, tabId, group) {
        // Encontrar el modal padre para no afectar a otros modales
        const modal = buttonElement.closest('.modal-content');
        
        // Remover clase active de los botones de este modal
        modal.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        // Remover clase active de los tabs de este grupo
        document.querySelectorAll('.tab-group-' + group).forEach(tab => tab.classList.remove('active'));
        
        // Activar seleccionados
        buttonElement.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    }

    function previewImage(input, previewContainerId) {
        const container = document.getElementById(previewContainerId);
        const img = container.querySelector('img');
        const icon = container.querySelector('i');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'block';
                if(icon) icon.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // AJAX Cascading Selects
    function loadMunicipios(estadoId, suffix = '') {
        const selectMunicipio = document.getElementById('select-municipio' + suffix);
        const selectParroquia = document.getElementById('select-parroquia' + suffix);
        
        selectMunicipio.innerHTML = '<option value="">Cargando...</option>';
        selectMunicipio.disabled = true;
        selectParroquia.innerHTML = '<option value="">Seleccione primero el Municipio...</option>';
        selectParroquia.disabled = true;

        if(!estadoId) return;

        fetch(`/api/municipios/${estadoId}`)
            .then(response => response.json())
            .then(data => {
                selectMunicipio.innerHTML = '<option value="">Seleccione Municipio...</option>';
                data.forEach(mun => {
                    selectMunicipio.innerHTML += `<option value="${mun.municipio_id}">${mun.municipio}</option>`;
                });
                selectMunicipio.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching municipios:', error);
                selectMunicipio.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    function loadParroquias(municipioId, suffix = '') {
        const selectParroquia = document.getElementById('select-parroquia' + suffix);
        
        selectParroquia.innerHTML = '<option value="">Cargando...</option>';
        selectParroquia.disabled = true;

        if(!municipioId) return;

        fetch(`/api/parroquias/${municipioId}`)
            .then(response => response.json())
            .then(data => {
                selectParroquia.innerHTML = '<option value="">Seleccione Parroquia...</option>';
                data.forEach(par => {
                    selectParroquia.innerHTML += `<option value="${par.parroquia_id}">${par.parroquia}</option>`;
                });
                selectParroquia.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching parroquias:', error);
                selectParroquia.innerHTML = '<option value="">Error al cargar</option>';
            });
    }
</script>
@endsection
