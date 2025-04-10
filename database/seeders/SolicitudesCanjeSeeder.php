<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use App\Models\SolicitudesCanje;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SolicitudesCanjeSeeder extends Seeder
{   
    private function getDiasTranscurridosSolicitudesCanje($fechaHoraEmisionVentaIntermediada) {
        $fechaEmision = \Carbon\Carbon::parse($fechaHoraEmisionVentaIntermediada);
        $dias = $fechaEmision->diffInDays(now());
        return $dias;
    }

    public function run(): void
    {
        $maxdayscanje = Setting::where('key', 'maxdaysCanje')->value('value') ?? 90;

        $solicitudescanjes = [
            [
                'idSolicitudCanje' => 'SOLICANJ-00001',
                'idVentaIntermediada' => 'F001-00000444', // En espera
                'idTecnico' => '77043114', // Josué García
                'nombreTecnico' => 'Josué Daniel García Betancourt',
                'idEstadoSolicitudCanje' => 2, // Aprobado
                'fechaHoraEmision_VentaIntermediada' => optional(VentaIntermediada::find('F001-00000444'))->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $this->getDiasTranscurridosSolicitudesCanje(optional(VentaIntermediada::find('F001-00000444'))->fechaHoraEmision_VentaIntermediada),
                'puntosComprobante_SolicitudCanje' => 500,  
                'puntosActuales_SolicitudCanje' => 500,
                'puntosCanjeados_SolicitudCanje' => 405,
                'puntosRestantes_SolicitudCanje' => 95, 
                'fechaHora_SolicitudCanje' => '2025-01-22 11:00:00',
                'idUser' => 1,
                'userName' => User::find(1)->value('name'),
                'comentario_SolicitudCanje' => 'Ok',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00002',
                'idVentaIntermediada' => 'F001-00000444', // Redimido (parcial)
                'idTecnico' => '77043114', // Josué García
                'nombreTecnico' => 'Josué Daniel García Betancourt',
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => optional(VentaIntermediada::find('F001-00000444'))->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $this->getDiasTranscurridosSolicitudesCanje(optional(VentaIntermediada::find('F001-00000444'))->fechaHoraEmision_VentaIntermediada),
                'puntosComprobante_SolicitudCanje' => 500,
                'puntosActuales_SolicitudCanje' => 95,
                'puntosCanjeados_SolicitudCanje' => 25,
                'puntosRestantes_SolicitudCanje' => 70, 
                'fechaHora_SolicitudCanje' => '2025-01-23 10:00:00',
                'comentario_SolicitudCanje' => 'No registrado aún',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00003',
                'idVentaIntermediada' => 'F001-00000555', // En espera (solicitado desde app)
                'idTecnico' => '77665544', // Manuel Carrasco
                'nombreTecnico' => 'Manuel Carrasco',
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => optional(VentaIntermediada::find('F001-00000555'))->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $this->getDiasTranscurridosSolicitudesCanje(optional(VentaIntermediada::find('F001-00000555'))->fechaHoraEmision_VentaIntermediada),
                'puntosComprobante_SolicitudCanje' => 1000,  
                'puntosActuales_SolicitudCanje' => 1000,
                'puntosCanjeados_SolicitudCanje' => 5,
                'puntosRestantes_SolicitudCanje' => 995, 
                'fechaHora_SolicitudCanje' => '2025-01-22 10:00:00',
                'comentario_SolicitudCanje' => 'No registrado aún',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00004',
                'idVentaIntermediada' => 'F001-00000666', // En espera (solicitado desde app)
                'idTecnico' => '77665544', // Manuel Carrasco
                'nombreTecnico' => 'Manuel Carrasco',
                'idEstadoSolicitudCanje' => 1, // Pendiente
                'fechaHoraEmision_VentaIntermediada' => optional(VentaIntermediada::find('F001-00000666'))->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $this->getDiasTranscurridosSolicitudesCanje(optional(VentaIntermediada::find('F001-00000666'))->fechaHoraEmision_VentaIntermediada),
                'puntosComprobante_SolicitudCanje' => 100,  
                'puntosActuales_SolicitudCanje' => 100,
                'puntosCanjeados_SolicitudCanje' => 100,
                'puntosRestantes_SolicitudCanje' => 0, 
                'fechaHora_SolicitudCanje' => '2025-01-22 10:00:00',
                'comentario_SolicitudCanje' => 'No registrado aún',
            ],
            [
                'idSolicitudCanje' => 'SOLICANJ-00005',
                'idVentaIntermediada' => 'F001-00000888', //En espera
                'idTecnico' => '77043114', // Josué García
                'nombreTecnico' => 'Josué Daniel García Betancourt',
                'idEstadoSolicitudCanje' => 3, // Rechazado
                'fechaHoraEmision_VentaIntermediada' => optional(VentaIntermediada::find('F001-00000888'))->fechaHoraEmision_VentaIntermediada,
                'diasTranscurridos_SolicitudCanje' => $this->getDiasTranscurridosSolicitudesCanje(optional(VentaIntermediada::find('F001-00000888'))->fechaHoraEmision_VentaIntermediada),
                'puntosComprobante_SolicitudCanje' => 100,  
                'puntosActuales_SolicitudCanje' => 100,
                'puntosCanjeados_SolicitudCanje' => 100,
                'puntosRestantes_SolicitudCanje' => 0, 
                'fechaHora_SolicitudCanje' => '2025-02-01 10:00:00',
                'idUser' => 2,
                'userName' => User::find(2)?->name,
                'comentario_SolicitudCanje' => 'Denegado',
            ],
        ];

        Controller::printJson($solicitudescanjes);
        Controller::printJson(User::all());

        foreach ($solicitudescanjes as $solCanj) {
            SolicitudesCanje::create($solCanj);
        }
    }
}
