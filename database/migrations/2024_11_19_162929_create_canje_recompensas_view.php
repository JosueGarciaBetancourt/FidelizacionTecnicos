<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCanjeRecompensasView extends Migration
{
    public function up()
    {
        // Eliminar la vista si ya existe para evitar el error de duplicado
        DB::statement('DROP VIEW IF EXISTS canje_recompensas_view');

        DB::statement('
            CREATE VIEW canje_recompensas_view AS
            SELECT 
                CanjesRecompensas.idCanje,
                CanjesRecompensas.idRecompensa,
                Recompensas.idTipoRecompensa,
                TiposRecompensas.nombre_TipoRecompensa,
                Recompensas.descripcionRecompensa,
                CanjesRecompensas.cantidad,
                CanjesRecompensas.costoRecompensa,
                (CanjesRecompensas.cantidad * CanjesRecompensas.costoRecompensa) AS puntosTotales,
                CanjesRecompensas.created_at AS canjeRecompensa_created_at
            FROM 
                CanjesRecompensas
            JOIN 
                Canjes ON CanjesRecompensas.idCanje = Canjes.idCanje
            JOIN 
                Recompensas ON CanjesRecompensas.idRecompensa = Recompensas.idRecompensa
            JOIN 
                TiposRecompensas ON Recompensas.idTipoRecompensa = TiposRecompensas.idTipoRecompensa
            ORDER BY 
                CanjesRecompensas.idRecompensa ASC
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS canje_recompensas_view');
    }
}
