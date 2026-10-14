<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $table = 'informes';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];
}
