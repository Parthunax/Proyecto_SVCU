@extends('layouts.app')

@section('title', 'Configuración General')

@section('styles')
<style>
    .config-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 0.75rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(15, 23, 42, 0.3);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        color: var(--text-main);
        transition: border-color 0.3s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--primary);
    }
    .profile-photo-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary);
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="header-actions" style="margin-bottom: 2rem;">
    <h1 class="page-title"><i class="fa-solid fa-gear"></i> Configuración General</h1>
</div>

<div class="config-card" style="max-width: 800px;">
    <h3><i class="fa-solid fa-user"></i> Mi Perfil</h3>
    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 1rem 0 2rem 0;">

    <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
            
            <!-- Columna Izquierda: Foto y Modo -->
            <div style="flex: 1; min-width: 250px; text-align: center;">
                <div>
                    @if(Auth::user()->Foto)
                        <img src="{{ asset(Auth::user()->Foto) }}" alt="Foto de Perfil" class="profile-photo-preview" id="photoPreview">
                    @else
                        <div class="profile-photo-preview" style="background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; margin: 0 auto 1rem auto;">
                            {{ strtoupper(substr(Auth::user()->usuario, 0, 1)) }}
                        </div>
                        <img src="" alt="Vista previa" class="profile-photo-preview" id="photoPreview" style="display:none; margin: 0 auto 1rem auto;">
                    @endif
                </div>
                <div class="form-group" style="text-align: left;">
                    <label>Actualizar Foto (PNG, JPG):</label>
                    <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*" onchange="previewImage(event)">
                </div>

                <div class="form-group" style="text-align: left; margin-top: 2rem; padding: 1rem; background: rgba(0,0,0,0.1); border-radius: 0.5rem;">
                    <label style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; margin: 0;">
                        <span><i class="fa-solid fa-moon"></i> / <i class="fa-solid fa-sun"></i> Alternar Tema</span>
                        <button type="button" class="btn btn-secondary" onclick="toggleTheme()" style="padding: 0.5rem;">
                            Cambiar
                        </button>
                    </label>
                </div>
            </div>

            <!-- Columna Derecha: Contraseña -->
            <div style="flex: 2; min-width: 300px;">
                <h4 style="margin-bottom: 1rem; color: var(--text-muted);">Cambiar Contraseña (Opcional)</h4>
                
                <div class="form-group">
                    <label>Contraseña Actual</label>
                    <input type="password" name="current_password" class="form-control" placeholder="Dejar en blanco si no desea cambiarla">
                </div>
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Mínimo 6 caracteres">
                </div>
                <div class="form-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Repita la nueva contraseña">
                </div>
            </div>

        </div>

        <div style="text-align: right; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('photoPreview');
                img.src = e.target.result;
                img.style.display = 'block';
                
                // Ocultar el div de la letra si existía
                var textAvatar = document.querySelector('div.profile-photo-preview');
                if(textAvatar) textAvatar.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
