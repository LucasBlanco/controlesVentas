<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Validacion extends Model
{
    protected $table = 'validaciones';
    protected $fillable = ['codem', 'afip', 'super', 'id_venta'];
}
