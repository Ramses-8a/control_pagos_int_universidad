<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Proyectos</h1>
                        <a href="{{ route('proyectos.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-lg transition-colors">
                            + Nuevo Proyecto
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b-2 border-slate-200">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Nombre</th>
                                    <th class="py-3 px-4 font-semibold">Precio</th>
                                    <th class="py-3 px-4 font-semibold">Estatus</th>
                                    <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proyectos as $proyecto)
                                <tr class="border-b border-slate-200 hover:bg-slate-50">
                                    
                                    <td class="py-3 px-4 font-medium">
                                        <a href="{{ route('proyectos.show', $proyecto) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $proyecto->nombre }}
                                        </a>
                                    </td>
                                    
                                    <td class="py-3 px-4">${{ number_format($proyecto->precio, 2) }}</td>
                                    
                                    <td class="py-3 px-4">
                                        @php
                                            // Lógica de color (esto está bien)
                                            $colorClass = 'bg-gray-100 text-gray-800 border-gray-300'; // Default
                                            if ($proyecto->estatusProyecto) {
                                                switch ($proyecto->estatusProyecto->id) {
                                                    case 1: $colorClass = 'bg-green-100 text-green-800 border-green-300'; break;
                                                    case 2: $colorClass = 'bg-red-100 text-red-800 border-red-300'; break;
                                                    case 3: $colorClass = 'bg-blue-100 text-blue-800 border-blue-300'; break;
                                                }
                                            }
                                        @endphp

                                        <select name="estatus_proyecto_id" 
                                                class="estatus-select rounded-full font-semibold text-sm py-1 px-2 border {{ $colorClass }} transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                                data-id="{{ $proyecto->id }}"
                                                data-url="{{ route('proyectos.actualizarEstatus', $proyecto) }}">
                                            
                                            @foreach ($estatuses as $estatus)
                                                <option value="{{ $estatus->id }}" 
                                                    @selected($proyecto->estatus_proyecto_id == $estatus->id)>
                                                    {{ $estatus->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="status-msg-{{ $proyecto->id }}" class="text-green-600 text-xs ml-2" style="display: none;"></span>
                                    </td>
                                    
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('proyectos.edit', $proyecto) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Editar</a>
                                        
                                        <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('¿Seguro que quieres cambiar el estatus a inactivo?');">
                                            @csrf
                                            @method('DELETE')
                                            
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-slate-500">Aún no hay proyectos creados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const selectoresEstatus = document.querySelectorAll('.estatus-select');

            // --- Función para cambiar el color del <select> ---
            function actualizarColorSelect(selectElement, nuevoEstatusId) {
                selectElement.classList.remove(
                    'bg-green-100', 'text-green-800', 'border-green-300',
                    'bg-red-100', 'text-red-800', 'border-red-300',
                    'bg-blue-100', 'text-blue-800', 'border-blue-300',
                    'bg-gray-100', 'text-gray-800', 'border-gray-300'
                );

                switch (String(nuevoEstatusId)) {
                    case '1': // Activo
                        selectElement.classList.add('bg-green-100', 'text-green-800', 'border-green-300');
                        break;
                    case '2': // Inactivo
                        selectElement.classList.add('bg-red-100', 'text-red-800', 'border-red-300');
                        break;
                    case '3': // Completado
                        selectElement.classList.add('bg-blue-100', 'text-blue-800', 'border-blue-300');
                        break;
                    default:
                        selectElement.classList.add('bg-gray-100', 'text-gray-800', 'border-gray-300');
                }
            }

            selectoresEstatus.forEach(select => {
                select.addEventListener('change', function () {
                    const nuevoEstatusId = this.value;
                    const url = this.dataset.url;
                    const proyectoId = this.dataset.id;
                    const statusMsg = document.getElementById(`status-msg-${proyectoId}`);
                    const selectElement = this; // Guardamos la referencia

                    statusMsg.style.display = 'none';

                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken, 
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            // 3. El body del JSON debe enviar 'estatus_proyecto_id'
                            estatus_proyecto_id: nuevoEstatusId 
                        })
                    })
                    .then(response => {
                        if (!response.ok) { throw new Error('Error en la respuesta'); }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Actualiza el color
                            actualizarColorSelect(selectElement, nuevoEstatusId);
                            
                            statusMsg.innerText = 'Guardado!';
                            statusMsg.style.color = 'green';
                            statusMsg.style.display = 'inline';
                            setTimeout(() => { statusMsg.style.display = 'none'; }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        statusMsg.innerText = 'Error';
                        statusMsg.style.color = 'red';
                        statusMsg.style.display = 'inline';
                    });
                });
            });
        });
    </script>
    @endpush
    @stack('scripts')
</x-app-layout>