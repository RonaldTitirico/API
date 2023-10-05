<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    use HasFactory;
    protected $table = 'universidades';
    protected $primaryKey = 'id';
    protected $fillable = [ 'nombre','direccion','descripcion'];


    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'universidades_carreras', 'id_universidad', 'id_carrera');
    }
}
