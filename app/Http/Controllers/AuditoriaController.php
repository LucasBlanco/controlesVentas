<?php

namespace App\Http\Controllers;

use App\Helpers\Sagas;
use App\Helpers\SagasProcess;
use App\services\AuditoriaService;
use App\services\VentaServiceGateway;
use Carbon\Carbon;
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

        $this->storeValidacion($request);

        $rutasAudios = array_fill(0, 3, null);

        $rutas = array_map(
            function ($index) use ($request) {
                if ($index + 1 > $request['cantidadAudios']) {
                    return null;
                }
                return "http://gestionarturnos.com/ventas/auditorias/" .
                    $request['idVenta'] . "/AM-" . $request['idVenta'] . "-" . $index . "-" . Carbon::today()->toDateTimeString() . ".mp3";
            },
            array_keys($rutasAudios)
        );

        $toStore = collect($request->all())->put('rutas', $rutas);
        $toAuditoriaVenta = collect($request->all());

        $storeProcess = new SagasProcess(
            [$this->service, 'store'],
            [$this->service, 'compensateStore'],
            $toStore
        );

        $auditoriaVentaProcess = new SagasProcess(
            [$this->ventaSrv, 'auditar'],
            [$this->ventaSrv, 'compensateAuditar'],
            $toAuditoriaVenta
        );

        $sagas = new Sagas(
            collect([
                $auditoriaVentaProcess,
                $storeProcess
            ]));

        $response = $sagas->execute();

        return response()->json($response->data, $response->status);

    }

    private function storeValidacion(Request $request){
        $this->validate($request,
            [
                'idVenta' => 'required|integer',
                'estado' => 'required|in:OK,OBS,RECHAZO',
                'observacion' => 'filled',
                'capitas' => 'filled|integer',
                'adherentes' => 'filled',
                'cantidadAudios' => 'required|integer',
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
                'cantidadAudios' => 'cantidad de audios',
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
}
