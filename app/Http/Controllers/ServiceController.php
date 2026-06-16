<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    // Para obtener los servicios a través de AJAX
    public function index(): JsonResponse
    {
        $services = Service::all(['id', 'name', 'price']);
        return response()->json($services);
    }
}
