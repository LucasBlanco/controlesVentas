<?php

use App\services\VentaServiceGateway;
use App\Validacion;
use GuzzleHttp\Promise\FulfilledPromise;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Mockery as m;

class ValidacionControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testValidar()
    {
        $ventaSrvMock = m::mock('overload:\App\Services\VentaServiceGateway');

        $ventaSrvMock->shouldReceive('validar')->andReturn(2);

        $validacion = factory(Validacion::class)->make();
        $validacion->idVenta = $validacion->id_venta;
        $validacion->superr = $validacion->super;

        $this->post('validaciones', $validacion->toArray());
        print_r($this->response->getContent());

        $this->seeInDatabase('validaciones', ['id_venta' => 1, 'super' => true, 'afip' => true, 'codem' => true]);
    }

    public function testValidarConCompensacion()
    {
        $ventaSrvMock = m::mock('overload:\App\Services\VentaServiceGateway');


        $ventaSrvMock->shouldReceive('validar')->andReturn(2);

        $validacion = factory(Validacion::class)->make();
        $validacion->idVenta = $validacion->id_venta;
        $validacion->superr = $validacion->super;

        $this->post('validaciones', $validacion->toArray());
        print_r($this->response->getContent());

        $this->seeInDatabase('validaciones', ['id_venta' => 1, 'super' => true, 'afip' => true, 'codem' => true]);
    }

}