<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parroquia extends Model {
    use SoftDeletes;
    protected $table = 'parroquias';
    protected $primaryKey = 'parroquia_id';
    protected $fillable = ['municipio_id', 'parroquia'];

    public function municipioObj() {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'municipio_id');
    }
}