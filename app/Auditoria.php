<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{

    protected $table = 'auditorias';
    protected $fillable = ['audio1', 'audio2', 'audio3', 'observacion', 'adherentes', 'id_venta'];
}
