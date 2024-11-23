<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSolicitudCanjeRecompensasView extends Migration
{
    public function up()
    {
        // Eliminar la vista si ya existe para evitar el error de duplicado
        DB::statement('DROP VIEW IF EXISTS solicitudCanje_recompensas_view');

        DB::statement('
            CREATE VIEW solicitudCanje_recompensas_view AS
            SELECT 
                SolicitudesCanjesRecompensas.idSolicitudCanje,
                SolicitudesCanjesRecompensas.idRecompensa,
                Recompensas.idTipoRecompensa,
                TiposRecompensas.nombre_TipoRecompensa,
                Recompensas.descripcionRecompensa,
                SolicitudesCanjesRecompensas.cantidad,
                SolicitudesCanjesRecompensas.costoRecompensa,
                (SolicitudesCanjesRecompensas.cantidad * SolicitudesCanjesRecompensas.costoRecompensa) AS puntosTotales,
                SolicitudesCanjesRecompensas.created_at AS solicitudCanjeRecompensa_created_at
            FROM 
                SolicitudesCanjesRecompensas
            JOIN 
                SolicitudesCanjes ON SolicitudesCanjesRecompensas.idSolicitudCanje = SolicitudesCanjes.idSolicitudCanje
            JOIN 
                Recompensas ON SolicitudesCanjesRecompensas.idRecompensa = Recompensas.idRecompensa
            JOIN 
                TiposRecompensas ON Recompensas.idTipoRecompensa = TiposRecompensas.idTipoRecompensa
            ORDER BY
                SolicitudesCanjesRecompensas.idRecompensa ASC
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS solicitudCanje_recompensas_view');
    }
}
