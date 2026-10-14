<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    protected $table = 'recomendaciones';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['madre','padre','paciente','escuela_procedencia','grado','recomendacion','notas'];
}
