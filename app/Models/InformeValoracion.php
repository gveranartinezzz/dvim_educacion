<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformeValoracion extends Model
{
    protected $table = 'informes_valoracion';
    protected $primaryKey = 'id';

    protected $guarded = [];
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = 'fecha_actualizacion';
    public $timestamps = true;

    public function paciente() { return $this->belongsTo(Paciente::class, 'id_paciente'); }
    public function usuario() { return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario'); }
}
