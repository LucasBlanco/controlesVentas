<?php

namespace App\Http\Controllers;

use App\Helpers\SagasProcess;
use App\Helpers\Sagas;
use App\services\ValidacionService;
use App\services\VentaServiceGateway;
use Illuminate\Http\Request;

class ValidacionController extends Controller
{
    private $service;
    private $ventaSrv;

    public function __construct(ValidacionService $val, VentaServiceGateway $venta)
    {
        $this->service = $val;
        $this->ventaSrv = $venta;
    }

    public function store(Request $request)
    {
        $idVenta = $request['idVenta'];
        $codem = $request['codem'];
        $afip = $request['afip'];
        $super = $request['superr'];

        $validacionExitosa = ($codem && $afip && $super);
        $validacion = collect($request->all())->except(['codem', 'afip', 'superr']);


        $storeProcess = new SagasProcess(
            [$this->service, 'store'],
            [$this->service, 'compensateStore'],
            collect($request->all())
        );

        $validacionVentaProcess = new SagasProcess(
            [$this->ventaSrv, 'validar'],
            [$this->ventaSrv, 'compensateValidar'],
            $validacion->put('validacionExitosa', $validacionExitosa)
        );

        $sagas = new Sagas(
            collect([
                $validacionVentaProcess,
                $storeProcess
            ]));

        $response = $sagas->execute();

        return response()->json($response->data, $response->status);

    }

    public function all()
    {
        return $this->service->all();
    }


}
