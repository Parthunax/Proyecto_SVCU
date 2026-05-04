<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable {
    use SoftDeletes;
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    protected $fillable = ['nun_documento', 'usuario', 'Contrasena', 'rol', 'Foto', 'estadus', 'ultimo_acceso'];
    protected $hidden = ['Contrasena'];
    protected $casts = [
        'ultimo_acceso' => 'datetime',
    ];

    public function getAuthPassword() {
        return $this->Contrasena;
    }

    public function rolObj() {
        return $this->belongsTo(Rol::class, 'rol', 'rol_id');
    }

    public function policia() {
        return $this->hasOne(Policia::class, 'usuario_id', 'usuario_id');
    }
}