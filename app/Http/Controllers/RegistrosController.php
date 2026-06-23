<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Barber;
use Illuminate\Http\Request;

class RegistrosController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['barber', 'service', 'client'])
            ->where('status', 'pending')
            ->orderBy('appointment_date', 'asc');

        if ($request->barber_id) {
            $query->where('barber_id', $request->barber_id);
        }

        if ($request->metodo && $request->metodo !== 'todos') {
            $query->where('payment_method', $request->metodo);
        }

        $appointments = $query->get();
        $barbers = Barber::with('user')->get();

        $total_cobrado      = $appointments->sum(fn($a) => $a->service->price ?? 0);
        $total_comisiones   = $total_cobrado * 0.60;
        $ganancias_barberos = $total_cobrado * 0.40;

        return view('registros.index', compact(
            'appointments',
            'barbers',
            'total_cobrado',
            'total_comisiones',
            'ganancias_barberos'
        ));
    }

    public function export(Request $request)
    {
        $query = Appointment::with(['barber', 'service', 'client'])
            ->where('status', 'pending')
            ->orderBy('appointment_date', 'asc');

        if ($request->barber_id) {
            $query->where('barber_id', $request->barber_id);
        }

        if ($request->metodo && $request->metodo !== 'todos') {
            $query->where('payment_method', $request->metodo);
        }

        $appointments = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registros_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($appointments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Fecha', 'Cliente', 'Barbero', 'Servicio', 'Método', 'Total', 'Comisión (60%)', 'Barbero (40%)']);

            foreach ($appointments as $a) {
                $precio = $a->service->price ?? 0;
                fputcsv($handle, [
                    $a->appointment_date->format('d/m/Y H:i'),
                    $a->client->name ?? '—',
                    $a->barber->name ?? '—',
                    $a->service->name ?? '—',
                    $a->payment_method ?? '—',
                    number_format($precio, 2),
                    number_format($precio * 0.60, 2),
                    number_format($precio * 0.40, 2),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
