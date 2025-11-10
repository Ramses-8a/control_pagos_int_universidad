<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @php
                $isEdit = isset($proyecto);
            @endphp
            {{ __($isEdit ? 'Editar Proyecto' : 'Crear Nuevo Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ $isEdit ? route('proyectos.update', $proyecto) : route('proyectos.store') }}" method="POST">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="col-span-2">
                                <label for="nombre" class="block font-semibold mb-1">Nombre del Proyecto</label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $proyecto->nombre ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <div class="col-span-2 p-4 border border-slate-200 rounded-lg">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block font-semibold">Desglose de Costos (Extras)</label>
                                    <button type="button" id="btn-agregar-costo" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-3 py-1 rounded-lg text-sm transition-colors">
                                        + Agregar Costo
                                    </button>
                                </div>

                                <div id="contenedor-costos" class="space-y-2">
                                    </div>

                                <input type="hidden" name="costo" id="costo_total_hidden" value="{{ old('costo', $proyecto->costo ?? 0) }}">

                                <div class="mt-4">
                                    <label for="costo_total_display" class="block font-semibold mb-1">Costo Total ($)</label>
                                    <input type="text" id="costo_total_display" value="${{ number_format(old('costo', $proyecto->costo ?? 0), 2) }}" class="w-full p-2 bg-slate-100 border border-slate-300 rounded-lg font-bold" readonly>
                                </div>
                            </div>
                            
                            <div>
                                <label for="precio" class="block font-semibold mb-1">Precio de Venta ($)</label>
                                <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio', $proyecto->precio ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="estatus_proyecto_id" class="block font-semibold mb-1">Estatus del Proyecto</label>
                                <select name="estatus_proyecto_id" id="estatus_proyecto_id" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">-- Seleccione un estatus --</option>
                                    @foreach ($estatuses as $estatus)
                                        <option value="{{ $estatus->id }}" 
                                            @selected(old('estatus_proyecto_id', $proyecto->estatus_proyecto_id ?? '') == $estatus->id)>
                                            {{ $estatus->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="fecha_inicio" class="block font-semibold mb-1">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', $proyecto->fecha_inicio ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="fecha_fin" class="block font-semibold mb-1">Fecha de Fin (Opcional)</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $proyecto->fecha_fin ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div class="col-span-2">
                                <label for="descripcion" class="block font-semibold mb-1">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="4" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion', $proyecto->descripcion ?? '') }}</textarea>
                            </div>

                        </div> <div class="mt-8 flex justify-end gap-4">
                            <a href="{{ route('proyectos.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-5 py-2 rounded-lg transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-lg transition-colors">
                                {{ $isEdit ? 'Actualizar Proyecto' : 'Guardar Proyecto' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const contenedorCostos = document.getElementById('contenedor-costos');
            const btnAgregarCosto = document.getElementById('btn-agregar-costo');
            const inputCostoTotalHidden = document.getElementById('costo_total_hidden');
            const inputCostoTotalDisplay = document.getElementById('costo_total_display');

            // --- CORRECCIÓN 1: ---
            // Leemos el costo original con el que carga la página (puede ser 0 si es 'crear')
            const costoOriginal = parseFloat(inputCostoTotalHidden.value) || 0;

            // 1. Función para calcular el total
            function calcularTotal() {
                // --- CORRECCIÓN 2: ---
                // Empezamos la suma desde el costoOriginal, NO desde 0.
                let total = costoOriginal; 
                
                // Selecciona todos los inputs de monto (solo los NUEVOS)
                const montos = contenedorCostos.querySelectorAll('.costo-monto');
                
                montos.forEach(function (input) {
                    total += parseFloat(input.value) || 0; // Suma el valor (o 0 si está vacío)
                });

                // Actualiza el input oculto que se enviará
                inputCostoTotalHidden.value = total.toFixed(2);
                // Actualiza el input visible para el usuario
                inputCostoTotalDisplay.value = `$${total.toFixed(2)}`;
            }

            // 2. Función para agregar un nuevo campo de costo
            function agregarCampo() {
                const nuevaFila = document.createElement('div');
                nuevaFila.className = 'flex gap-2 items-center';
                
                nuevaFila.innerHTML = `
                    <input type="text" name="costo_concepto[]" placeholder="Concepto" class="costo-concepto w-3/5 p-2 border border-slate-300 rounded-lg text-sm">
                    <input type="number" step="0.01" placeholder="Monto" class="costo-monto w-2/5 p-2 border border-slate-300 rounded-lg text-sm">
                    <button type="button" class="btn-quitar-costo bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded-lg text-xs">X</button>
                `;
                
                contenedorCostos.appendChild(nuevaFila);
            }

            // 3. Event Listeners 
            
            btnAgregarCosto.addEventListener('click', agregarCampo);

            contenedorCostos.addEventListener('click', function(e) {
                // Si se hace clic en un botón de "quitar"
                if (e.target.classList.contains('btn-quitar-costo')) {
                    e.target.parentElement.remove(); // Elimina la fila entera
                    calcularTotal(); // Recalcula el total
                }
            });

            contenedorCostos.addEventListener('input', function(e) {
                // Si se escribe en un input de "monto"
                if (e.target.classList.contains('costo-monto')) {
                    calcularTotal(); // Recalcula el total
                }
            });
        });
    </script>
    @endpush
    </x-app-layout>