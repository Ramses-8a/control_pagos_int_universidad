<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{--Ver si es el formulario de para agg o edit--}}
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

                            <div>
                                <label for="costo" class="block font-semibold mb-1">Costo ($)</label>
                                <input type="number" step="0.01" name="costo" id="costo" value="{{ old('costo', $proyecto->costo ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="precio" class="block font-semibold mb-1">Precio de Venta ($)</label>
                                <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio', $proyecto->precio ?? '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="fecha_inicio" class="block font-semibold mb-1">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio', $isEdit ? ($proyecto->fecha_inicio ? \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('Y-m-d') : '') : '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="fecha_fin" class="block font-semibold mb-1">Fecha de Fin (Opcional)</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin', $isEdit ? ($proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->format('Y-m-d') : '') : '') }}" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <div class="col-span-2">
                                <label for="descripcion" class="block font-semibold mb-1">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="4" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('descripcion', $proyecto->descripcion ?? '') }}</textarea>
                            </div>
                            @if($isEdit)
                            <div>
                                <label for="estatus" class="block font-semibold mb-1">Estatus</label>
                                <select name="estatus" id="estatus" class="w-full p-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="activo" {{ $proyecto->estatus == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ $proyecto->estatus == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            @endif
                        </div>

                        <div class="mt-8 flex justify-end gap-4">
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
</x-app-layout>

