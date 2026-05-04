<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model {
    use SoftDeletes;
    protected $table = 'municipios';
    protected $primaryKey = 'municipio_id';
    protected $fillable = ['estado_id', 'municipio'];

    public function estadoObj() {
        return $this->belongsTo(Estado::class, 'estado_id', 'estado_id');
    }
}