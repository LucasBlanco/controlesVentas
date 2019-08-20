<?php

use App\Helpers\SagasProcess;
use App\Helpers\Sagas;
use App\services\PromiseService;

class SagasTest extends TestCase
{


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSagasProcessSuccess()
    {


        $service = new Prueba();

        $sagasProcess = new SagasProcess(
            [$service, 'exito'],
            [$service, 'exito'],
            'hola'
        );

        $sagas = new Sagas(collect([$sagasProcess, $sagasProcess]));

        $response = $sagas->execute()->data;

        $this->assertEquals(
            $response, ['hola', 'hola']
        );

    }

    public function testSagasProcessError()
    {
        $service = new Prueba();

        $sagasProcess = new SagasProcess(
            [$service, 'exito'],
            [$service, 'saluda'],
            'hola'
        );

        $sagasProcessError = new SagasProcess(
            [$service, 'error'],
            [$service, 'saluda'],
            'hola'
        );

        $sagas = new Sagas(collect([$sagasProcess, $sagasProcessError]));

        $response = $sagas->execute();


        $this->assertInstanceOf(Exception::class, $response);

    }
}

class Prueba{

    private $promiseSrv;

    /**
     * Prueba constructor.
     * @param $promiseSrv
     */
    public function __construct()
    {
        $this->promiseSrv = new PromiseService();
    }


    public function exito($algo){
        return $algo;
    }

    public function error($algo){
        try {
            return 1/0;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function saluda(){
        echo 'hola';
    }
}