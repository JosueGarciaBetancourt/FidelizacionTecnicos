<?php

namespace App\Http\Controllers;

use GuzzleHttp\RetryMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{   
    public function configuracion()
    {
        return view('dashboard.configuracion');
    }
}
