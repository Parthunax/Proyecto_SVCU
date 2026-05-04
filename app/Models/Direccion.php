<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model {
    use SoftDeletes;
    protected $table = 'direccion';
    protected $primaryKey = 'vivienda_id';
    protected $fillable = ['parroquia_id', 'localidad', 'tipo_vivienda', 'ruta', 'nun_vivienda'];

    public function parroquia() {
        return $this->belongsTo(Parroquia::class, 'parroquia_id', 'parroquia_id');
    }
}