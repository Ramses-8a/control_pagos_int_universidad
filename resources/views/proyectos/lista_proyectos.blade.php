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
                                    <td class="py-3 px-4 font-medium">{{ $proyecto->nombre }}</td>
                                    <td class="py-3 px-4">${{ number_format($proyecto->precio, 2) }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-3 py-1 text-sm rounded-full font-semibold {{ $proyecto->estatus == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($proyecto->estatus) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('proyectos.edit', $proyecto) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Editar</a>
                                        <form action="{{ route('proyectos.destroy', $proyecto) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('¿Seguro que quieres cambiar el estatus a inactivo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Borrar</button>
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
</x-app-layout>

