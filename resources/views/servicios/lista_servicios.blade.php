<x-app-layout>
    <div x-data="{ deleteUrl: '' }">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
                @endif
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold">Gestión de servicios</h1>

                            <div class="flex gap-4">
                                <a href="{{ route('tipo_servicios.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold px-5 py-2 rounded-lg transition-colors">
                                    Administrar tipos de servicio
                                </a>

                                <a href="{{ route('servicios.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2 rounded-lg transition-colors">
                                    Agregar un nuevo servicio
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="border-b-2 border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4 font-semibold">Nombre</th>
                                        <th class="py-3 px-4 font-semibold">Descripción</th>
                                        <th class="py-3 px-4 font-semibold">Costo</th>
                                        <th class="py-3 px-4 font-semibold">Precio</th>
                                        <th class="py-3 px-4 font-semibold">Tipo de servicio</th>
                                        <th class="py-3 px-4 font-semibold">Estatus</th>
                                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($servicios as $servicio)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                                        <td class="py-3 px-4">{{ $servicio->nombre }}</td>
                                        <td class="py-3 px-4">{{ $servicio->descripcion }}</td>
                                        <td class="py-3 px-4">${{ number_format($servicio->costo, 2) }}</td>
                                        <td class="py-3 px-4">${{ number_format($servicio->precio, 2) }}</td>
                                        <td class="py-3 px-4">{{ $servicio->tipoServicio->nombre }}</td>
                                        <td class="py-3 px-4">
                                            @if ($servicio->estatus == 'activo')
                                            <span class="px-3 py-1 text-sm rounded-full font-semibold bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                            @else
                                            <span class="px-3 py-1 text-sm rounded-full font-semibold bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap">
                                            <a href="{{ route('servicios.edit', $servicio) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Editar</a>
                                            
                                            <button type="button" 
                                                    x-on:click.prevent="$dispatch('open-modal', 'confirm-service-deletion'); deleteUrl = '{{ route('servicios.destroy', $servicio) }}'"
                                                    class="text-red-500 hover:text-red-700 font-semibold ml-4">
                                                Borrar
                                            </button>
                                            
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="7" class="py-6 text-slate-500">No hay servicios registrados.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <x-modal name="confirm-service-deletion" focusable>
                    <form :action="deleteUrl" method="POST" class="p-6">
                        @csrf
                        @method('DELETE')
    
                        <h2 class="text-lg font-medium text-gray-900">
                            ¿Estás seguro de que quieres eliminar este servicio?
                        </h2>
                
                        <p class="mt-1 text-sm text-gray-600">
                            Una vez eliminado, no podrás recuperar sus datos.
                        </p>
                
                        <div class="mt-6 flex justify-end">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                Cancelar
                            </x-secondary-button>
                
                            <x-danger-button class="ml-3">
                                Eliminar Servicio
                            </x-danger-button>
                        </div>
                    </form>
                </x-modal>

            </div>
        </div>
    </div> </x-app-layout>