@extends('layouts.app')

@section('title', 'Gestión de Ubicaciones')

@section('styles')
    <style>
        /* ── Tabs ── */
        .tab-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
            gap: 0;
        }

        .tab-nav {
            display: flex;
            flex: 1;
        }

        .tab-btn {
            padding: 0.85rem 1.5rem;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.95rem;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: white;
        }

        .tab-btn.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            font-weight: bold;
        }

        /* Add button sits right-aligned inside tab header */
        .tab-add-btn {
            margin-left: auto;
            margin-right: 0;
            padding: 0.45rem 1rem;
            font-size: 0.85rem;
            border-radius: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
            align-self: center;
            margin-bottom: 2px;
            /* align with border-bottom visual */
        }

        /* ── Tab content ── */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Form controls ── */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .form-control {
            width: 100%;
            padding: 0.65rem 0.85rem;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border-color);
            border-radius: 0.45rem;
            color: white;
            transition: border-color 0.3s;
            font-size: 0.95rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }

        /* ── Table ── */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table th {
            padding: 0.75rem 1rem;
            border-bottom: 2px solid var(--border-color);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(51, 65, 85, 0.5);
            color: var(--text-main);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* ── Pagination — override Laravel's default large SVG arrows ── */
        .pager-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
            width: 100%;
        }

        .pager-wrap nav {
            display: flex;
            align-items: center;
        }

        .pager-wrap nav>div:first-child {
            display: none;
        }

        /* hide "Showing X to Y…" */
        .pager-wrap span[role="status"] {
            display: none;
            justify-content: center;
        }

        /* All anchor/span inside pagination */
        .pager-wrap .pagination {
            display: flex;
            gap: 0.2rem;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pager-wrap .pagination li a,
        .pager-wrap .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 10rem;
            height: 10rem;
            font-size: 0.78rem;
            border-radius: 50rem;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            text-decoration: none;
            background: rgba(15, 23, 42, 0.4);
            transition: all 0.2s;
        }

        .pager-wrap .pagination li a:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .pager-wrap .pagination li.active span,
        .pager-wrap [aria-current="page"] span {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
            font-weight: 600;
        }

        /* Kill the huge SVGs on prev/next arrows */
        .pager-wrap svg {
            width: 0.75rem;
            height: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 class="page-title" style="margin-bottom: 0;"><i class="fa-solid fa-map-location-dot"></i> Gestión de Ubicaciones
        </h1>
    </div>

    @if(session('success'))
        <div class="alert"
            style="background: rgba(34,197,94,0.2); border:1px solid #22c55e; color:#4ade80; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert"
            style="background: rgba(239,68,68,0.2); border:1px solid #ef4444; color:#f87171; padding:1rem; border-radius:0.5rem; margin-bottom:1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: flex; justify-content: center;">
        <div class="card" style="width: 100%; max-width: 900px;">

            {{-- ── Tab Header: nav tabs + add button on the right ── --}}
            <div class="tab-header">
                <div class="tab-nav">
                    <button class="tab-btn active" onclick="switchTab('estados', event)">Estados</button>
                    <button class="tab-btn" onclick="switchTab('municipios', event)">Municipios</button>
                    <button class="tab-btn" onclick="switchTab('parroquias', event)">Parroquias</button>
                </div>
                {{-- Shown/hidden via JS based on active tab --}}
                <button id="btnAddEstado" class="btn btn-primary tab-add-btn" onclick="openModal('modalEstado')">
                    <i class="fa-solid fa-plus"></i> Añadir Estado
                </button>
                <button id="btnAddMunicipio" class="btn btn-primary tab-add-btn" style="display:none;"
                    onclick="openModal('modalMunicipio')">
                    <i class="fa-solid fa-plus"></i> Añadir Municipio
                </button>
                <button id="btnAddParroquia" class="btn btn-primary tab-add-btn" style="display:none;"
                    onclick="openModal('modalParroquia')">
                    <i class="fa-solid fa-plus"></i> Añadir Parroquia
                </button>
            </div>

            {{-- ── ESTADOS ── --}}
            <div id="tab-estados" class="tab-content active">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estado</th>
                                <th>ISO 3166-2</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estados as $est)
                                <tr>
                                    <td>{{ $est->estado_id }}</td>
                                    <td>{{ $est->estado }}</td>
                                    <td>{{ $est->{'iso_3166-2'} ?? '—' }}</td>
                                    <td>
                                        <button class="btn btn-secondary" style="padding:0.3rem 0.55rem;"
                                            onclick="openEditEstado({{ $est->estado_id }}, '{{ addslashes($est->estado) }}', '{{ $est->{"iso_3166-2"} }}')">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pager-wrap">{{ $estados->fragment('tab-estados')->links() }}</div>
            </div>

            {{-- ── MUNICIPIOS ── --}}
            <div id="tab-municipios" class="tab-content">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Municipio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($municipios as $mun)
                                <tr>
                                    <td>{{ $mun->municipio_id }}</td>
                                    <td>{{ $mun->municipio }}</td>
                                    <td>{{ $mun->estadoObj->estado ?? '—' }}</td>
                                    <td>
                                        <button class="btn btn-secondary" style="padding:0.3rem 0.55rem;"
                                            onclick="openEditMunicipio({{ $mun->municipio_id }}, '{{ addslashes($mun->municipio) }}', {{ $mun->estado_id }})">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pager-wrap">{{ $municipios->fragment('tab-municipios')->links() }}</div>
            </div>

            {{-- ── PARROQUIAS ── --}}
            <div id="tab-parroquias" class="tab-content">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Parroquia</th>
                                <th>Municipio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parroquias as $par)
                                <tr>
                                    <td>{{ $par->parroquia_id }}</td>
                                    <td>{{ $par->parroquia }}</td>
                                    <td>{{ $par->municipioObj->municipio ?? '—' }}</td>
                                    <td>
                                        <button class="btn btn-secondary" style="padding:0.3rem 0.55rem;"
                                            onclick="openEditParroquia({{ $par->parroquia_id }}, '{{ addslashes($par->parroquia) }}', {{ $par->municipio_id }})">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pager-wrap">{{ $parroquias->fragment('tab-parroquias')->links() }}</div>
            </div>

        </div>{{-- end .card --}}
    </div>

    {{-- ══════════ MODALES ══════════ --}}

    {{-- Modal Estado --}}
    <div id="modalEstado" class="modal-overlay">
        <div class="modal-content" style="max-width: 440px;">
            <div class="modal-header">
                <h3 id="modalEstadoTitle"><i class="fa-solid fa-map"></i> Añadir Estado</h3>
                <button class="btn-close" onclick="closeModal('modalEstado')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formEstado" method="POST" action="{{ route('estados.store') }}">
                @csrf
                <input type="hidden" name="_method" id="estadoMethod" value="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Estado *</label>
                        <input type="text" name="estado" id="estadoNombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>ISO 3166-2 (Opcional)</label>
                        <input type="text" name="iso_3166_2" id="estadoIso" class="form-control" maxlength="4">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEstado')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Municipio --}}
    <div id="modalMunicipio" class="modal-overlay">
        <div class="modal-content" style="max-width: 440px;">
            <div class="modal-header">
                <h3 id="modalMunicipioTitle"><i class="fa-solid fa-city"></i> Añadir Municipio</h3>
                <button class="btn-close" onclick="closeModal('modalMunicipio')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formMunicipio" method="POST" action="{{ route('municipios.store') }}">
                @csrf
                <input type="hidden" name="_method" id="municipioMethod" value="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Municipio *</label>
                        <input type="text" name="municipio" id="municipioNombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="estado_id" id="municipioEstado" class="form-control" required>
                            <option value="">Seleccione un Estado</option>
                            @foreach($todosEstados as $est)
                                <option value="{{ $est->estado_id }}">{{ $est->estado }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalMunicipio')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Parroquia --}}
    <div id="modalParroquia" class="modal-overlay">
        <div class="modal-content" style="max-width: 440px;">
            <div class="modal-header">
                <h3 id="modalParroquiaTitle"><i class="fa-solid fa-church"></i> Añadir Parroquia</h3>
                <button class="btn-close" onclick="closeModal('modalParroquia')"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formParroquia" method="POST" action="{{ route('parroquias.store') }}">
                @csrf
                <input type="hidden" name="_method" id="parroquiaMethod" value="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre de la Parroquia *</label>
                        <input type="text" name="parroquia" id="parroquiaNombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Municipio *</label>
                        <select name="municipio_id" id="parroquiaMunicipio" class="form-control" required>
                            <option value="">Seleccione un Municipio</option>
                            @foreach($todosMunicipios as $mun)
                                <option value="{{ $mun->municipio_id }}">{{ $mun->municipio }}
                                    ({{ $mun->estadoObj->estado ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalParroquia')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const addBtns = {
            estados: document.getElementById('btnAddEstado'),
            municipios: document.getElementById('btnAddMunicipio'),
            parroquias: document.getElementById('btnAddParroquia'),
        };

        function switchTab(tabId, event) {
            // Tabs
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');

            // Add buttons
            Object.values(addBtns).forEach(b => b.style.display = 'none');
            addBtns[tabId].style.display = 'flex';
        }

        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            if (id === 'modalEstado') {
                document.getElementById('formEstado').reset();
                document.getElementById('formEstado').action = "{{ route('estados.store') }}";
                document.getElementById('estadoMethod').value = 'POST';
                document.getElementById('modalEstadoTitle').innerHTML = '<i class="fa-solid fa-map"></i> Añadir Estado';
            } else if (id === 'modalMunicipio') {
                document.getElementById('formMunicipio').reset();
                document.getElementById('formMunicipio').action = "{{ route('municipios.store') }}";
                document.getElementById('municipioMethod').value = 'POST';
                document.getElementById('modalMunicipioTitle').innerHTML = '<i class="fa-solid fa-city"></i> Añadir Municipio';
            } else if (id === 'modalParroquia') {
                document.getElementById('formParroquia').reset();
                document.getElementById('formParroquia').action = "{{ route('parroquias.store') }}";
                document.getElementById('parroquiaMethod').value = 'POST';
                document.getElementById('modalParroquiaTitle').innerHTML = '<i class="fa-solid fa-church"></i> Añadir Parroquia';
            }
        }

        // Close on backdrop click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(overlay.id); });
        });

        // Edit helpers
        function openEditEstado(id, nombre, iso) {
            document.getElementById('modalEstadoTitle').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Estado';
            document.getElementById('formEstado').action = `/estados/${id}`;
            document.getElementById('estadoMethod').value = 'PUT';
            document.getElementById('estadoNombre').value = nombre;
            document.getElementById('estadoIso').value = iso;
            openModal('modalEstado');
        }

        function openEditMunicipio(id, nombre, estado_id) {
            document.getElementById('modalMunicipioTitle').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Municipio';
            document.getElementById('formMunicipio').action = `/municipios/${id}`;
            document.getElementById('municipioMethod').value = 'PUT';
            document.getElementById('municipioNombre').value = nombre;
            document.getElementById('municipioEstado').value = estado_id;
            openModal('modalMunicipio');
        }

        function openEditParroquia(id, nombre, municipio_id) {
            document.getElementById('modalParroquiaTitle').innerHTML = '<i class="fa-solid fa-pen"></i> Editar Parroquia';
            document.getElementById('formParroquia').action = `/parroquias/${id}`;
            document.getElementById('parroquiaMethod').value = 'PUT';
            document.getElementById('parroquiaNombre').value = nombre;
            document.getElementById('parroquiaMunicipio').value = municipio_id;
            openModal('modalParroquia');
        }

        // Mantener la pestaña activa al recargar o cambiar de página
        document.addEventListener("DOMContentLoaded", function () {
            let hash = window.location.hash;
            if (hash && hash.startsWith('#tab-')) {
                let tabName = hash.replace('#tab-', '');
                let tabBtn = document.querySelector(`.tab-btn[onclick*="${tabName}"]`);
                if (tabBtn) {
                    tabBtn.click();
                }
            }
        });
    </script>
@endsection