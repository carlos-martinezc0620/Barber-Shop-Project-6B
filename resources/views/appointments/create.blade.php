@extends('layouts.admin')

@section('title', 'Agendar Cita')

@section('content')
<div class="max-w-6xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Agendar Nueva Cita</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- COLUMNA IZQUIERDA: Calendario + Hora -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Selecciona Fecha y Hora</h3>
            
            <form id="appointmentForm" class="space-y-6">
                <!-- CALENDARIO -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Fecha</label>
                    <input type="date" id="date" name="date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" min="{{ date('Y-m-d') }}">
                </div>

                <!-- HORA, MINUTOS, AM/PM -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Horas</label>
                        <select id="hour" name="hour" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Selecciona</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Minutos</label>
                        <select id="minute" name="minute" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecciona</option>
                            @for($i = 0; $i <= 50; $i += 10)
                                <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Período</label>
                        <select id="period" name="period" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <option value="">Selecciona</option>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- COLUMNA DERECHA: Servicios + Barberos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Selecciona Servicio y Barbero</h3>

            <div class="space-y-6">
                <!-- SERVICIOS -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-3">Tipo de Servicio</label>
                    <select id="service_id" name="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="checkFormValidity()">
                        <option value="">-- Selecciona un servicio --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} - ${{ number_format($service->price, 2) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- BARBEROS (Dinámicos) -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-3">Selecciona Barbero</label>
                    <select id="barber_id" name="barber_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500" onchange="checkFormValidity()">
                        <option value="">-- Selecciona un barbero --</option>
                        @foreach($barbers as $barber)
                            <option value="{{ $barber->user_id }}">{{ $barber->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- BOTÓN RESERVAR -->
                <button id="submitBtn" type="submit" form="appointmentForm" class="w-full bg-yellow-500 hover:bg-yellow-600 disabled:bg-gray-400 text-white font-bold py-3 px-4 rounded-lg transition cursor-not-allowed" disabled onclick="submitAppointment(event)">
                    Reservar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Validar campos y habilitar botón "Reservar"
function checkFormValidity() {
    const date = document.getElementById('date').value;
    const hour = document.getElementById('hour').value;
    const minute = document.getElementById('minute').value;
    const period = document.getElementById('period').value;
    const serviceId = document.getElementById('service_id').value;
    const barberId = document.getElementById('barber_id').value;

    const isValid = date && hour && minute !== '' && period && serviceId && barberId;
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = !isValid;
    submitBtn.classList.toggle('cursor-not-allowed', !isValid);
    submitBtn.classList.toggle('cursor-pointer', isValid);
}

// Monitorear cambios en los campos
document.getElementById('date').addEventListener('change', checkFormValidity);
document.getElementById('hour').addEventListener('change', checkFormValidity);
document.getElementById('minute').addEventListener('change', checkFormValidity);
document.getElementById('period').addEventListener('change', checkFormValidity);
document.getElementById('service_id').addEventListener('change', checkFormValidity);

// Enviar formulario
function submitAppointment(event) {
    event.preventDefault();

    const formData = {
        date: document.getElementById('date').value,
        hour: document.getElementById('hour').value,
        minute: document.getElementById('minute').value,
        period: document.getElementById('period').value,
        service_id: document.getElementById('service_id').value,
        barber_id: document.getElementById('barber_id').value,
    };

    fetch('{{ route("appointments.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify(formData),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('¡Cita reservada exitosamente!');
            document.getElementById('appointmentForm').reset();
            checkFormValidity();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al guardar la cita');
    });
}

// Inicializar validación
checkFormValidity();
</script>
@endsection