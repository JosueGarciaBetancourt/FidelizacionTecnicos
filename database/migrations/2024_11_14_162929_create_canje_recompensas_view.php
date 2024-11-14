<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCanjeRecompensasView extends Migration
{
    public function up()
    {
        DB::statement('
            CREATE VIEW canje_recompensas_view AS
            SELECT 
                CanjesRecompensas.idCanje,
                CanjesRecompensas.idRecompensa,
                Recompensas.tipoRecompensa,
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
            ORDER BY 
                CanjesRecompensas.idRecompensa ASC
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS canje_recompensas_view');
    }
}
