<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistorialDelictivo extends Model {
    use SoftDeletes;
    protected $table = 'historial_delictivo';
    protected $primaryKey = 'historial_id';
    protected $fillable = ['delito_id', 'persona_id', 'fecha_delito', 'descripcion', 'estatus'];

    public function delito() {
        return $this->belongsTo(Delito::class, 'delito_id', 'delito_id');
    }

    public function persona() {
        return $this->belongsTo(Persona::class, 'persona_id', 'nun_documento');
    }
}