<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @php
                
                $isEdit = isset($proyecto) && $proyecto->exists;
                
                if ($isEdit) {
                    $title = 'Editar Proyecto';
                    $formAction = route('proyectos.update', $proyecto);
                } else {
                    $title = 'Crear Nuevo Proyecto';
                    $formAction = route('proyectos.store');
                }
            @endphp
            {{ __($title) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ $formAction }}" method="POST">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            
                            <div class="col-span-2">
                                <label for="nombre" class="block font-semibold mb-1">Nombre del Proyecto</label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $proyecto->nombre ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                    
                            <div class="col-span-2">
                                <label for="empleados" class="block font-semibold mb-1">Asignar Equipo de Trabajo</label>
                                <div class="text-xs text-gray-500 mb-2">
                                    <i class="fas fa-info-circle"></i> Mantén presionada la tecla <strong>Ctrl</strong> para seleccionar varios.
                                </div>
                                <select name="empleados[]" id="empleados" multiple class="w-full p-2 border border-slate-300 rounded-lg h-40 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @foreach ($empleados as $empleado)
                                        <option value="{{ $empleado->id }}" 
                                            
                                            @selected(in_array($empleado->id, old('empleados', $isEdit ? $proyecto->empleados->pluck('id')->toArray() : [])))
                                        >
                                            {{ $empleado->nombre }} {{ $empleado->apaterno }} {{ $empleado->amaterno ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-2 p-4 border border-slate-200 rounded-lg bg-slate-50">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block font-semibold text-gray-700">Desglose de Costos Extras</label>
                                    <button type="button" id="btn-agregar-costo" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-3 py-1 rounded-lg text-sm shadow-sm transition-colors">
                                         Agregar Costo
                                    </button>
                                </div>

                                <div id="contenedor-costos" class="space-y-2">
                                    @if ($isEdit && $proyecto->costos->isNotEmpty())
                                        @foreach ($proyecto->costos as $costo)
                                            <div class="flex gap-2 items-center">
                                                <input type="text" name="concepto[]" placeholder="Concepto" value="{{ $costo->concepto }}" class="costo-concepto w-3/5 p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <input type="number" step="0.01" name="monto[]" placeholder="Monto" value="{{ $costo->monto }}" class="costo-monto w-2/5 p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <button type="button" class="btn-quitar-costo bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs shadow-sm transition-colors">X</button>
                                            </div>
                                        @endforeach
                                    @else
                                    
                                        <div class="flex gap-2 items-center">
                                            <input type="text" name="concepto[]" placeholder="Concepto (ej. Licencias, Internet)" class="costo-concepto w-3/5 p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <input type="number" step="0.01" name="monto[]" placeholder="Monto" class="costo-monto w-2/5 p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <button type="button" class="btn-quitar-costo bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs shadow-sm transition-colors">X</button>
                                        </div>
                                    @endif
                                </div>

                                
                                <div class="mt-4 flex justify-end items-center gap-2">
                                    <span class="text-gray-600 font-semibold text-sm">Total Extras: </span>
                                    <span id="costo_total_display" class="font-bold text-lg text-indigo-600">$0.00</span>
                                   
                                    <input type="hidden" name="costo" id="costo_total_hidden">
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

                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <a href="{{ route('proyectos.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-5 py-2 rounded-lg transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-lg transition-colors shadow-lg">
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
            const inputCostoTotalDisplay = document.getElementById('costo_total_display');
            const inputCostoTotalHidden = document.getElementById('costo_total_hidden');

            function calcularTotal() {
                let total = 0;
                const montos = contenedorCostos.querySelectorAll('.costo-monto');
                montos.forEach(function (input) {
                    total += parseFloat(input.value) || 0;
                });
                
                const formatter = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' });
                inputCostoTotalDisplay.innerText = formatter.format(total);
                
                
                if(inputCostoTotalHidden) {
                    inputCostoTotalHidden.value = total.toFixed(2);
                }
            }

            function agregarCampo() {
                const nuevaFila = document.createElement('div');
                nuevaFila.className = 'flex gap-2 items-center';
                nuevaFila.innerHTML = `
                    <input type="text" name="concepto[]" placeholder="Concepto" class="costo-concepto w-3/5 p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <input type="number" step="0.01" name="monto[]" placeholder="Monto" class="costo-monto w-2/5 p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <button type="button" class="btn-quitar-costo bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-xs shadow-sm transition-colors">X</button>
                `;
                contenedorCostos.appendChild(nuevaFila);
            }

            btnAgregarCosto.addEventListener('click', agregarCampo);

            contenedorCostos.addEventListener('click', function(e) {
                
                if (e.target.classList.contains('btn-quitar-costo') || e.target.innerText === 'X') {
                   
                    const row = e.target.closest('.flex'); 
                    if(row && row.parentElement === contenedorCostos) {
                        row.remove();
                        calcularTotal();
                    }
                }
            });

            contenedorCostos.addEventListener('input', function(e) {
                if (e.target.classList.contains('costo-monto')) {
                    calcularTotal();
                }
            });

            calcularTotal();
        });
    </script>
    @endpush
</x-app-layout>