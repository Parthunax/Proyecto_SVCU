<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policia extends Model {
    use SoftDeletes;
    protected $table = 'policia';
    protected $primaryKey = 'Policia_id';
    protected $fillable = ['usuario_id', 'nun_documento', 'nombre', 'apellido', 'sexo', 'fecha_nac', 'telefono', 'especialidad', 'Grado'];

    public function usuarioObj() {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuario_id');
    }
}