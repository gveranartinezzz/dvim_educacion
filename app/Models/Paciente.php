<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nombre','fecha_nacimiento','direccion','edad','diagnostico','estatus','motivo_consulta','escolarizado','madre','padre'];

    public function avances() { return $this->hasMany(Avance::class, 'paciente_id'); }
    public function valoraciones() { return $this->hasMany(InformeValoracion::class, 'id_paciente'); }
}
