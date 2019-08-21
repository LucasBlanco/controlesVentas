<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function validate(Request $request, array $rules, array $atributos = [], array $mensajes = [])
    {
        $m = [
            'required' => 'El campo :attribute es requerido',
            'exists'    => 'No existe el/la :attribute ',
            'integer' => 'El campo :attribute tiene que ser un entero',
            'in' => 'El valor enviado en :attribute no se encuentra dentro de las opciones',
            'boolean' => 'El campo :attribute tiene que ser verdadero o falso',
            '*.required_with' => 'El campo :attribute es requerido'
        ];
        parent::validate( $request,  $rules,  $m,  $atributos);
    }
}
