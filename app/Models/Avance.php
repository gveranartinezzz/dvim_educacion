<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    protected $table = 'avances_pacientes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $guarded = [];

    public function paciente() { return $this->belongsTo(Paciente::class, 'paciente_id'); }
    public function usuario() { return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario'); }
}
