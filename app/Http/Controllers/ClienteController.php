<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = User::where('role', 'user')
            ->withCount('appointments_as_client as visit_count')
            ->orderBy('name')
            ->get();

        $selectedCliente = null;
        $historial       = collect();
        $stats           = null;

        if ($request->client_id) {
            $selectedCliente = User::find($request->client_id);

            if ($selectedCliente) {
                $historial = Appointment::with(['service', 'barber'])
                    ->where('client_id', $selectedCliente->id)
                    ->orderBy('appointment_date', 'desc')
                    ->get();

                $totalGastado  = $historial->sum(fn($a) => $a->service->price ?? 0);
                $totalVisitas  = $historial->count();
                $promedio      = $totalVisitas > 0 ? $totalGastado / $totalVisitas : 0;
                $ultimaVisita  = $historial->first()?->appointment_date;

                $barberoFrecuenteId = $historial
                    ->groupBy('barber_id')
                    ->sortByDesc(fn($g) => $g->count())
                    ->keys()
                    ->first();

                $barberoNombre = $historial->firstWhere('barber_id', $barberoFrecuenteId)?->barber?->name ?? '—';

                $stats = compact('totalGastado', 'totalVisitas', 'promedio', 'ultimaVisita', 'barberoNombre');
            }
        }

        return view('clientes.index', compact('clientes', 'selectedCliente', 'historial', 'stats'));
    }

    public function quickStore(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $cliente = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make('barbershop2026'),
            'role'     => 'user',
        ]);

        return response()->json([
            'success' => true,
            'client'  => ['id' => $cliente->id, 'name' => $cliente->name, 'email' => $cliente->email],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $cliente = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make('barbershop2026'),
            'role'     => 'user',
        ]);

        return redirect()->route('clientes.index', ['client_id' => $cliente->id])
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function destroy(User $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    public function update(Request $request, User $cliente)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $cliente->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $cliente->update([
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('clientes.index', ['client_id' => $cliente->id])
            ->with('success', 'Cliente actualizado correctamente.');
    }
}
