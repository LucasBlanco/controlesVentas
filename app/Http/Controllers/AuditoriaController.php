<?php

namespace App\Http\Controllers;

use App\services\AuditoriaService;
use App\services\VentaServiceGateway;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{

    private $service;
    private $ventaSrv;

    public function __construct(AuditoriaService $service, VentaServiceGateway $ventaSrv)
    {
        $this->service = $service;
        $this->ventaSrv = $ventaSrv;
    }


    public function store(Request $request)
    {

        $observacion = $request['observacion'];
        $adherentes = $request['adherentes'];
        $idVenta = $request['idVenta'];
        $rutasAudios = array_fill(0, 3, null);

        $venta = $this->ventaSrv->getById($idVenta)->wait();

        $rutas = array_map(
            function ($index) use ($venta, $request) {
                if ($index + 1 > $request['cantidadAudios']) {
                    return null;
                }
                return "http://gestionarturnos.com/ventas/auditorias/" .
                    $venta->id . "/" . $venta->nombre . "-AM-" . $venta->id . "-" . $index . ".mp3";
            },
            array_keys($rutasAudios)
        );

        $this->service->auditar(
            $rutas,
            $observacion,
            $adherentes,
            $venta->id,
            collect($request->all())->except('observacion', 'adherentes', 'rutas')
        );

    }


}
