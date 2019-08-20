<?php
/**
 * Created by PhpStorm.
 * User: lblanco
 * Date: 08/03/19
 * Time: 17:03
 */

namespace App\services;


use App\Enums\Estados;
use App\Enums\Perfiles;
use App\Helpers\SagasProcess;
use App\services\DatosEmpresaService;
use App\services\Fecha\Fecha;
use App\services\VisitaService;
use App\User;
use App\Venta;
use App\Visita;
use Illuminate\Support\Collection;


class VentaServiceGateway
{

    private $preVentaServer;

    public function __construct()
    {
        $this->preVentaServer = new PromiseService('/preventa');
    }

    public function validar(Collection $request)
    {
        return $this->preVentaServer->post('validar', $request);
    }

    public function compensateValidar($idVenta)
    {
        return $this->preVentaServer->post('compensateValidar', $idVenta);
    }

    public function auditar(Collection $request)
    {
        return $this->preVentaServer->post('auditar', $request);
    }

    public function compensateAuditar(Collection $request)
    {
        return $this->preVentaServer->post('compensateAuditar', $request);
    }


    public function presentarVentas($ventas, $user, $fechaPresentacion)
    {
        $presentaciones = (object)[
            'idVentas' => $ventas,
            'fechaPresentacion' => $fechaPresentacion,
            'idUser' => $user
        ];
        return $this->preVentaServer->post('presentarVentas', $presentaciones);
    }

    public function getById($id)
    {
        return $this->preVentaServer->get('find/' . $id);
    }

    public function analizarPresentacion($venta, $estado, $recuperable, $observacion, $user)
    {
        $presentacion = (object)[
            'idVenta' => $venta,
            'recuperable' => $recuperable,
            'observacion' => $observacion,
            'idUser' => $user
        ];
        if ($estado == "PAGADA")
            return $this->preVentaServer->post('pagarVentaPresentacion', $presentacion);
        else if ($estado == "RECHAZADA")
            return $this->preVentaServer->post('rechazarVentaPresentacion', $presentacion);
        else {
            return $this->preVentaServer->post('pendienteAuditoriaPresentacion', $presentacion);
        }
    }

    public function completarVenta($idVenta, $cuit, $empresa, $tresPorciento)
    {
        $datosParaCompletar = (object)[
            'cuit' => $cuit,
            'empresa' => $empresa,
            'tresPorciento' => $tresPorciento
        ];
        return $this->preVentaServer->put('update/' . $idVenta, $datosParaCompletar);
    }

    public function rechazar($request)
    {
        $presentacion = (object)[
            'idVenta' => $request['idVenta'],
            'recuperable' => $request['recuperable'],
            'observacion' => $request['observacion'],
            'idUser' => $request['userId']
        ];
        return $this->preVentaServer->post('rechazarVentaPresentacion', $presentacion);
    }

}