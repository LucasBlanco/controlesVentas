<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 09/03/19
 * Time: 23:15
 */

namespace App\Services;


use App\Auditoria;
use Illuminate\Support\Collection;

class AuditoriaService
{
    private $ventaSrv;

    public function __construct(VentaServiceGateway $ventaSrv)
    {
        $this->ventaSrv = $ventaSrv;
    }

    public function auditar($audios, $observacion, $adherentes, $venta, Collection $auditoria)
    {

        $auditoriaPromise = $this->ventaSrv->auditar(
            $auditoria
        );

        $newAuditoria = new Auditoria();
        $newAuditoria->audio1 = $audios[0];
        $newAuditoria->audio2 = $audios[1];
        $newAuditoria->audio3 = $audios[2];
        $newAuditoria->observacion = $observacion;
        $newAuditoria->adherentes = $adherentes;
        $newAuditoria->id_venta = $venta;
        $newAuditoria->save();

        $auditoriaPromise->wait();

    }

    public function delete($auditorias)
    {
        $auditorias->each(function ($a) {
            $a->delete();
        });
    }

}