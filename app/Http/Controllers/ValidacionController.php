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

        $this->storeValidacion($request);

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

    public function storeValidacion(Request $request){

        $this->validate($request,
            [
                'idVenta' => 'required|integer',
                'codem' => 'required|boolean',
                'afip' => 'required|boolean',
                'superr' => 'required|boolean',
                'observacion' => 'filled',
                'capitas' => 'filled|integer',
                'datosEmpresa' => 'filled|array',
                'datosEmpresa.empresa' => 'required_with:datosEmpresa',
                'datosEmpresa.direccion' => 'required_with:datosEmpresa',
                'datosEmpresa.localidad' => 'required_with:datosEmpresa',
                'datosEmpresa.cantidadEmpleados' => 'sometimes|required_with:datosEmpresa',
                'datosEmpresa.horaEntrada' => 'sometimes|required_with:datosEmpresa',
                'datosEmpresa.horaSalida' => 'sometimes|required_with:datosEmpresa',
            ],
            [
                'idVenta' => 'preventa',
                'datosEmpresa' => 'empresa',
                'datosEmpresa.empresa' => 'razon social',
                'datosEmpresa.direccion' => 'direccion',
                'datosEmpresa.localidad' => 'localidad',
                'datosEmpresa.cantidadEmpleados' => 'cantidad de empleados',
                'datosEmpresa.horaEntrada' => 'hora entrada',
                'datosEmpresa.horaSalida' => 'hora salida',
            ]
        );
    }

    public function all()
    {
        return $this->service->all();
    }


}
