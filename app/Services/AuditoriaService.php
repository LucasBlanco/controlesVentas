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

    public function __construct()
    {
    }

    public function store(Collection $auditoria)
    {

        ['rutas' => $audios, 'observacion' => $observacion,
            'adherentes' => $adherentes, 'idVenta' => $idVenta] = $auditoria;

        $newAuditoria = new Auditoria();
        $newAuditoria->audio1 = $audios[0];
        $newAuditoria->audio2 = $audios[1];
        $newAuditoria->audio3 = $audios[2];
        $newAuditoria->observacion = $observacion;
        $newAuditoria->adherentes = $adherentes;
        $newAuditoria->id_venta = $idVenta;
        $newAuditoria->save();

    }

    public function compensateStore(Collection $auditoria){
        Auditoria::destroy($auditoria['idVenta']);
    }

    public function delete($auditorias)
    {
        $auditorias->each(function ($a) {
            $a->delete();
        });
    }

}