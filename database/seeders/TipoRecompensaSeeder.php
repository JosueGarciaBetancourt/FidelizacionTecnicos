<?php

namespace Database\Seeders;

use App\Models\TipoRecompensa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoRecompensaSeeder extends Seeder
{
    public function run(): void
    {
        TipoRecompensa::create([
            'idTipoRecompensa' => 1,
            'nombre_TipoRecompensa' => 'Efectivo',
            'colorTexto_TipoRecompensa' => '#139161', // Verde oscuro
            'colorFondo_TipoRecompensa' => '#DFF6E1', // Verde claro (contraste alto)
        ]);
        
        $tiposrecompensas = [
            [
                'idTipoRecompensa' => 2,
                'nombre_TipoRecompensa' => 'Accesorio',
                'colorTexto_TipoRecompensa' => '#9C27B0', // Morado oscuro
                'colorFondo_TipoRecompensa' => '#F3DAF9', // Morado claro (contrastado)
            ],
            [
                'idTipoRecompensa' => 3,
                'nombre_TipoRecompensa' => 'EPP',
                'colorTexto_TipoRecompensa' => '#2196F3', // Azul fuerte
                'colorFondo_TipoRecompensa' => '#D6EBFF', // Azul claro (contraste alto)
            ],
            [
                'idTipoRecompensa' => 4,
                'nombre_TipoRecompensa' => 'Herramienta',
                'colorTexto_TipoRecompensa' => '#FF9800', // Naranja fuerte
                'colorFondo_TipoRecompensa' => '#FFE6C7', // Naranja claro (buena visibilidad)
            ],
            [
                'idTipoRecompensa' => 5,
                'nombre_TipoRecompensa' => 'Probar Eliminar Tipo Recompensa',
                'colorTexto_TipoRecompensa' => '#D32F2F', // Rojo fuerte
                'colorFondo_TipoRecompensa' => '#FFDADA', // Rojo claro (buena visibilidad)
            ],
        ];
        
        foreach ($tiposrecompensas as $tipoRecompensa) {
            TipoRecompensa::create($tipoRecompensa);
        }
    }
}
