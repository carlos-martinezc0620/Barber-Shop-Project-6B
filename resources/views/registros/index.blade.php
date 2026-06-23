@extends('layouts.admin')

@section('title', 'Cobros y Pagos')

@section('content')
<div class="max-w-6xl">

    {{-- Header --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Cobros y Pagos</h1>
            <span class="text-gray-500 text-sm">Registro completo de transacciones</span>
        </div>
        <a href="{{ route('registros.export', request()->only(['barber_id','metodo'])) }}"
           class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-5 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
            </svg>
            Exportar Reporte
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="flex gap-6 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-5 flex-1 flex justify-between items-start">
            <div>
                <span class="text-sm text-gray-500">Total Cobrado</span>
                <div class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($total_cobrado, 2) }}</div>
            </div>
            <div class="bg-gray-100 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5 flex-1 flex justify-between items-start">
            <div>
                <span class="text-sm text-gray-500">Comisiones Barbería</span>
                <div class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($total_comisiones, 2) }}</div>
                <span class="text-xs text-gray-400 mt-1">60% del total</span>
            </div>
            <div class="bg-gray-100 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-10 10M7 7h10v10"/>
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5 flex-1 flex justify-between items-start">
            <div>
                <span class="text-sm text-gray-500">Ganancias Barberos</span>
                <div class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($ganancias_barberos, 2) }}</div>
                <span class="text-xs text-gray-400 mt-1">40% del total</span>
            </div>
            <div class="bg-gray-100 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a4 4 0 00-8 0v2M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-lg border border-gray-200 p-5 mb-6">
        <div class="flex justify-between items-center gap-6 flex-wrap">
            <div>
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wide block mb-2">Método de Pago</span>
                @php $metodoActual = request('metodo', 'todos'); @endphp
                <div class="flex gap-2 flex-wrap">
                    @foreach(['todos' => 'Todos los métodos', 'Efectivo' => 'Efectivo', 'Tarjeta' => 'Tarjeta', 'Transferencia' => 'Transferencia'] as $val => $label)
                        <a href="{{ route('registros.index', array_merge(request()->only('barber_id'), ['metodo' => $val])) }}"
                           class="flex items-center gap-1 text-sm px-4 py-1.5 rounded-full transition
                               {{ $metodoActual === $val ? 'bg-amber-500 text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wide block mb-2">Barbero</span>
                <form method="GET" action="{{ route('registros.index') }}">
                    <select name="barber_id" onchange="this.form.submit()"
                        class="border border-gray-200 text-gray-700 text-sm rounded-lg px-4 py-1.5 focus:outline-none focus:ring-2 focus:ring-amber-300">
                        <option value="">Todos los barberos</option>
                        @foreach($barbers as $barber)
                            <option value="{{ $barber->user_id }}" {{ request('barber_id') == $barber->user_id ? 'selected' : '' }}>
                                {{ $barber->user->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-lg border border-gray-200">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Fecha</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Cliente</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Barbero</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Servicio</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Método</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Total</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Comisión</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">Barbero</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    @php
                        $precio    = $appointment->service->price ?? 0;
                        $comision  = $precio * 0.60;
                        $ganancia  = $precio * 0.40;
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <div class="text-sm text-gray-800">{{ $appointment->appointment_date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $appointment->appointment_date->format('H:i') }}</div>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $appointment->client->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $appointment->barber->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $appointment->service->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $appointment->payment_method ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-gray-800 text-right">${{ number_format($precio, 2) }}</td>
                        <td class="px-5 py-3 text-sm text-amber-500 font-medium text-right">${{ number_format($comision, 2) }}</td>
                        <td class="px-5 py-3 text-sm text-gray-700 text-right">${{ number_format($ganancia, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-gray-400 text-sm">No hay registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
