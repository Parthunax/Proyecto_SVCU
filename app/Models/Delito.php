<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delito extends Model {
    use SoftDeletes;
    protected $table = 'delito';
    protected $primaryKey = 'delito_id';
    protected $fillable = ['Nombre', 'Tipo', 'cargo_penal'];
}