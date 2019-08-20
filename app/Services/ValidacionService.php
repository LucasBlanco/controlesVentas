<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 09/03/19
 * Time: 23:15
 */

namespace App\Services;

use App\User;
use App\Validacion;
use Exception;
use Illuminate\Support\Collection;

class ValidacionService
{

    private $ventaSrv;

    public function __construct(VentaServiceGateway $v)
    {
        $this->ventaSrv = $v;
    }

    public function all()
    {
        return []; //TODO: Hacer esto
    }


    public function store(
        Collection $validacion
    )
    {
        ['idVenta' => $idVenta, 'codem' => $codem, 'super' => $super, 'afip' => $afip] = $validacion;
        $newValidacion = new Validacion();
        $newValidacion->codem = $codem;
        $newValidacion->afip = $afip;
        $newValidacion->super = $super;
        $newValidacion->id_venta = $idVenta;
        $newValidacion->save();


    }

    public function compensateStore(Collection $validacion)
    {
        Validacion::destroy($validacion['idVenta']);
    }

    public function delete($validaciones)
    {
        $validaciones->each(function ($v) {
            $v->delete();
        });
    }

}