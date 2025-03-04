<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TipoRecompensa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $startTime = microtime(true); // Marca el inicio del tiempo
        
        $this->call(SettingsSeeder::class);
        $this->call(PerfilUsuarioSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(OficioSeeder::class);
        $this->call(TecnicoSeeder::class);
        $this->call(TecnicoOficioSeeder::class);
        $this->call(Login_tecnicoSeeder::class);
        $this->call(TipoRecompensaSeeder::class);
        $this->call(RecompensaSeeder::class);
        $this->call(EstadoVentaSeeder::class);
        $this->call(VentaIntermediadaSeeder::class);
        $this->call(CanjeSeeder::class);
        $this->call(CanjeRecompensaSeeder::class);
        $this->call(EstadoSolicitudCanjeSeeder::class);
        $this->call(SolicitudesCanjeSeeder::class);
        $this->call(SolicitudCanjeRecompensaSeeder::class);
       
        $endTime = microtime(true); // Marca el fin del tiempo
        $totalTime = $endTime - $startTime; // Calcula el tiempo total
        echo "Tiempo total seeders: " . round($totalTime, 2) . " seg\n"; // Muestra el tiempo total en ms
    }
}
