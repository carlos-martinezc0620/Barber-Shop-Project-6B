@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl">
    <!-- Encabezado -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Hola desde admin</h1>
    </div>

    <!-- SECCIÓN: Citas Programadas Hoy -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Citas Programadas Hoy</h2>
            
            <!-- FILTRO DE BARBEROS -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex gap-3">
                <select name="barber_id" id="barber_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" onchange="this.form.submit()">
                    <option value="">Todos los barberos</option>
                    @foreach($barbers as $barber)
                        <option value="{{ $barber->user_id }}" {{ $selectedBarberId == $barber->user_id ? 'selected' : '' }}>
                            {{ $barber->user->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- TABLA DE CITAS -->
        @if($todaysAppointments->isEmpty())
            <p class="text-gray-500 text-center py-8">No hay citas programadas para hoy</p>
        @else
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Hora</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Cliente</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Barbero</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Servicio</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Precio</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($todaysAppointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-800">{{ $appointment->appointment_date->format('H:i') }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $appointment->client->name }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $appointment->barber->name }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $appointment->service->name }}</td>
                            <td class="px-4 py-3 text-gray-800">${{ number_format($appointment->service->price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                                    {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection