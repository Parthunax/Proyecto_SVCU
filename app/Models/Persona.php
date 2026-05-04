<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model {
    use SoftDeletes;
    protected $table = 'persona';
    protected $primaryKey = 'nun_documento';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nun_documento', 'Nombre', 'Paterno', 'Materno', 'Genero', 'EstadoCivil', 'Telefono', 'FechaNacimiento', 'foto_cara', 'foto_huella', 'vivienda_id'];

    public function direccion() {
        return $this->belongsTo(Direccion::class, 'vivienda_id', 'vivienda_id');
    }

    public function historialDelictivo() {
        return $this->hasMany(HistorialDelictivo::class, 'persona_id', 'nun_documento');
    }

    public function vehiculos() {
        return $this->hasMany(Vehiculo::class, 'propietario', 'nun_documento');
    }
}