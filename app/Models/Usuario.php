<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $fillable = ['nombre','apaterno','amaterno','correo','password','rol','estado','dos_factores_activado','codigo_verificacion','fecha_codigo'];

    protected $hidden = ['password', 'codigo_verificacion', 'remember_token'];

    public function avances() { return $this->hasMany(Avance::class, 'usuario_id', 'id_usuario'); }
    public function horarios() { return $this->hasMany(Horario::class, 'id_usuario', 'id_usuario'); }
}
