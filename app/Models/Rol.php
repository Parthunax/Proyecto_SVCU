<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rol extends Model {
    use SoftDeletes;
    protected $table = 'roles';
    protected $primaryKey = 'rol_id';
    protected $fillable = ['nombre_rol', 'descripcion'];

    public function usuarios() {
        return $this->hasMany(Usuario::class, 'rol', 'rol_id');
    }
}