<?php

namespace App\Http\Controllers;

use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use App\Models\VentaIntermediada;
use App\Models\Canje;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{   
    
    public function canjes()
    {
        return view('dashboard.canjes');
    }

    public function tecnicos()
    {
        return view('dashboard.tecnicos');
    }

    public function configuracion()
    {
        return view('dashboard.configuracion');
    }
}
