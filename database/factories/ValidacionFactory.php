<?php

use Faker\Generator;

$factory->define(App\Validacion::class, function (Faker\Generator $faker) {
    return [
        'codem' => true,
        'afip' => true,
        'super' => true,
        'id_venta' => 1
    ];
});