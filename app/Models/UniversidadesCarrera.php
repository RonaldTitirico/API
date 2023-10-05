<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniversidadesCarrera extends Model
{
    protected $table = 'universidades_carreras';
    public $timestamps = true;
    // Indica que no necesitas timestamps en esta tabla
    // public $timestamps = false;

    // Define las columnas que se pueden llenar
    protected $fillable = ['id_universidad', 'id_carrera'];
}
