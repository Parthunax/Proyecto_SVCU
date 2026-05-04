<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReporteVehiculo extends Model {
    use SoftDeletes;
    protected $table = 'reporte_vehiculo';
    protected $primaryKey = 'reporte_id';
    protected $fillable = ['nun_placa', 'tipo_reporte', 'fecha_reporte'];

    public function vehiculoObj() {
        return $this->belongsTo(Vehiculo::class, 'nun_placa', 'nun_placa');
    }
}