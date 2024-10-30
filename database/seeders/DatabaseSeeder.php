<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(OficioSeeder::class);
        $this->call(TecnicoSeeder::class);
        $this->call(TecnicoOficioSeeder::class);
        $this->call(RecompensaSeeder::class);
        $this->call(EstadoVentaSeeder::class);
        $this->call(VentaIntermediadaSeeder::class);
        $this->call(CanjeSeeder::class);
        $this->call(Login_tecnicoSeeder::class);
    }
}
