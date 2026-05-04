<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarcaVehiculo extends Model {
    use SoftDeletes;
    protected $table = 'marcas_vehiculos';
    protected $primaryKey = 'marca_id';
    protected $fillable = ['nombre_marca', 'descripcion'];
}