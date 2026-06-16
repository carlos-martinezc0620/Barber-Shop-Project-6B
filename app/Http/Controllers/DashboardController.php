<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Esta declaración obtiene los barberos que se hayan registrado
        $barbers = Barber::with('user')->get();
        
        // Esta declaración obtiene las citas del mes actual
        $now = Carbon::now();
        $selectedBarberId = request('barber_id');

        $query = Appointment::whereYear('appointment_date', $now->year)
            ->whereMonth('appointment_date', $now->month)
            ->with(['client', 'barber', 'service']);

        if ($selectedBarberId) {
            $query->where('barber_id', $selectedBarberId);
        }

        $todaysAppointments = $query->orderBy('appointment_date')->get();

        return view('dashboard.index', [
            'barbers' => $barbers,
            'todaysAppointments' => $todaysAppointments,
            'selectedBarberId' => $selectedBarberId,
        ]);
    }
}