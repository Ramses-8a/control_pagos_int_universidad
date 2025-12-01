<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold">Gestión de clientes para servicios</h1>
                    <br>

                    @if($contactos->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay contactos aún</h3>
                        <p class="mt-1 text-sm text-gray-500">Espera a que los clientes llenen el formulario.</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b-2 border-slate-200">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">Cliente</th>
                                    <th class="py-3 px-4 font-semibold">Contacto</th>
                                    <th class="py-3 px-4 font-semibold">Interés</th>
                                    <th class="py-3 px-4 font-semibold">Fecha de recepción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contactos as $contacto)
                                <tr class="border-b border-slate-200 hover:bg-slate-50">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $contacto->nombre }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <div class="text-sm text-gray-900 flex items-center mb-1">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $contacto->correo }}
                                            </div>
                                            <div class="text-sm text-gray-900 flex items-center">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $contacto->telefono }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        @if($contacto->servicio)
                                        <span class="px-3 py-1 text-sm rounded-full font-semibold bg-blue-100 text-blue-800">
                                            {{ $contacto->servicio->nombre }}
                                        </span>
                                        @else
                                        <span class="px-3 py-1 text-sm rounded-full font-semibold bg-gray-100 text-gray-800">
                                            Interés General
                                        </span>
                                        @endif
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-500">
                                        {{ $contacto->created_at->locale('es')->isoFormat('D MMM YYYY, h:mm a') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($contactos->hasPages())
                    <div class="mt-4">
                        {{ $contactos->links() }}
                    </div>
                    @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>