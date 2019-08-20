<?php

use App\Auditoria;
use App\services\VentaServiceGateway;
use GuzzleHttp\Promise\FulfilledPromise;

use Mockery as m;

class ValidacionControllerTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    public function testAuditar()
    {
        $ventaSrvMock = m::mock('overload:\App\Services\VentaServiceGateway');

        $carlos = (object)[
            'id' => 1,
            'nombre' => 'Carlos'
        ];

        $response = new FulfilledPromise($carlos);
        $ventaSrvMock->shouldReceive('auditar')->andReturn($response);
        $ventaSrvMock->shouldReceive('getById')->andReturn($response);

        $auditoria = factory(Auditoria::class)->make();
        $auditoria->cantidadAudios = 2;

        $this->post('auditorias', $auditoria->toArray());
        print_r($this->response->getContent());
        $this->assertResponseOk();



        $this->seeInDatabase('auditorias', [
            'audio1' => 'hola',
            'audio2' => 'hoal',
            'audio3' => 'hola',
            'observacion' => 'hola',
            'adherentes' => 'adherentes',
            'id_venta' => 1
        ]);
    }

}