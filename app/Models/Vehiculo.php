<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculo extends Model {
    use SoftDeletes;
    protected $table = 'vehiculo';
    protected $primaryKey = 'nun_placa';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nun_placa', 'tipo_vehiculo', 'propietario', 'marca', 'modelo', 'color', 'año', 'serial_carroceria'];

    public function propietarioObj() {
        return $this->belongsTo(Persona::class, 'propietario', 'nun_documento');
    }

    public function marcaObj() {
        return $this->belongsTo(MarcaVehiculo::class, 'marca', 'marca_id');
    }

    public function reportes() {
        return $this->hasMany(ReporteVehiculo::class, 'nun_placa', 'nun_placa');
    }
}