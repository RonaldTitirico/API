<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;
    protected $table = 'carreras';
    protected $primaryKey = 'id';

    protected $fillable = ['nombre', 'descripcion', 'anio'];

    public function universidades()
    {
        return $this->belongsToMany(Universidad::class);
    }
}
