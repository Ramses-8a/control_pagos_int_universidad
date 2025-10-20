<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalles del Proyecto: {{ $proyecto->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6 pb-4 border-b">
                        <h1 class="text-2xl font-bold">{{ $proyecto->nombre }}</h1>
                        <div class="flex gap-3">
                            <a href="{{ route('proyectos.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-4 py-2 rounded-lg transition-colors">
                                 Volver a la Lista
                            </a>
                            <a href="{{ route('proyectos.edit', $proyecto) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg transition-colors">
                                Editar Proyecto
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Información General</h3>
                            <div class="mt-2 space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estatus</dt>
                                    <dd class="mt-1 text-md font-semibold">
                                        @php
                                            $colorClass = 'bg-gray-100 text-gray-800';
                                            $estatusNombre = 'Estatus Desconocido';
                                            if ($proyecto->estatusProyecto) {
                                                $estatusNombre = $proyecto->estatusProyecto->nombre;
                                                switch ($proyecto->estatusProyecto->id) {
                                                    case 1: $colorClass = 'bg-green-100 text-green-800'; break;
                                                    case 2: $colorClass = 'bg-red-100 text-red-800'; break;
                                                    case 3: $colorClass = 'bg-blue-100 text-blue-800'; break;
                                                }
                                            }
                                        @endphp
                                        <span class="px-3 py-1 rounded-full {{ $colorClass }}">
                                            {{ $estatusNombre }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                                    <dd class="mt-1 text-md">{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                                    <dd class="mt-1 text-md">
                                        {{ $proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->format('d/m/Y') : 'No definida' }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-600">Finanzas</h3>
                            <div class="mt-2 space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Costo del Proyecto</dt>
                                    <dd class="mt-1 text-md font-semibold text-red-600">${{ number_format($proyecto->costo, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Precio de Venta</dt>
                                    <dd class="mt-1 text-md font-semibold text-green-600">${{ number_format($proyecto->precio, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ganancia Estimada</dt>
                                    <dd class="mt-1 text-md font-semibold text-blue-600">${{ number_format($proyecto->precio - $proyecto->costo, 2) }}</dd>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <h3 class="text-lg font-semibold text-gray-600 mt-4">Descripción</h3>
                            <div class="mt-2 prose max-w-none text-gray-700">
                                <p>{{ $proyecto->descripcion ?? 'El proyecto no tiene una descripción.' }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>