<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 15/03/19
 * Time: 15:15
 */
$factory->define(App\Auditoria::class, function (Faker\Generator $faker) {


    return [
        'audio1' => 'hola',
        'audio2' => 'hoal',
        'audio3' => 'hola',
        'observacion' => 'hola',
        'adherentes' => 'adherentes',
        'id_venta' => 1
    ];
});