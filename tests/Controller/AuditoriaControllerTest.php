<?php

use App\Auditoria;
use App\services\VentaServiceGateway;
use Carbon\Carbon;
use GuzzleHttp\Promise\FulfilledPromise;

use Mockery as m;

class ValidacionControllerTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    public function testStore()
    {
        $ventaSrvMock = m::mock('overload:\App\Services\VentaServiceGateway');

        $carlos = (object)[
            'id' => 1,
            'nombre' => 'Carlos'
        ];

        $ventaSrvMock->shouldReceive('auditar')->andReturn($carlos);

        $auditoria = factory(Auditoria::class)->make();
        $rutaAudio1 ="http://gestionarturnos.com/ventas/auditorias/1/AM-1-0-" . Carbon::today()->toDateTimeString() . ".mp3";
        $rutaAudio2 ="http://gestionarturnos.com/ventas/auditorias/1/AM-1-1-" . Carbon::today()->toDateTimeString() . ".mp3";
        $auditoria->cantidadAudios = 2;
        $auditoria->idVenta = $auditoria["id_venta"];
        $auditoria->estado = 'OK';
        $auditoria->datosEmpresa = [];

        $this->post('auditorias', $auditoria->toArray());
        print_r($this->response->getContent());
        $this->assertResponseOk();

        $this->seeInDatabase('auditorias', [
            'audio1' => $rutaAudio1,
            'audio2' => $rutaAudio2,
            'audio3' => null,
            'observacion' => 'hola',
            'adherentes' => 'adherentes',
            'id_venta' => 1
        ]);
    }

    public function testStoreCompensation()
    {
        $auditoriaSrv = new \App\Services\AuditoriaService();

        $auditoria = factory(Auditoria::class)->make();

        $auditoria->rutas = [
            $auditoria->audio1,
            $auditoria->audio2,
            $auditoria->audio3
        ];
        $auditoria->idVenta = $auditoria->id_venta;

        $auditoriaSrv->store(collect($auditoria));

        $auditoriaSrv->compensateStore(collect($auditoria));


        $this->notSeeInDatabase('auditorias', [
            'audio1' => 'hola',
            'audio2' => 'hoal',
            'audio3' => 'hola',
            'observacion' => 'hola',
            'adherentes' => 'adherentes',
            'id_venta' => 1
        ]);
    }

}