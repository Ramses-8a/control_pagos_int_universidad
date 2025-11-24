<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <!-- botones-->
                   
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-200">
                        <h1 class="text-2xl font-bold text-indigo-700">{{ $proyecto->nombre }}</h1>
                        <div class="flex gap-3">
                            <a href="{{ route('proyectos.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-lg transition-colors border border-slate-300">
                                 Volver
                            </a>
                            <a href="{{ route('proyectos.edit', $proyecto) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg transition-colors shadow-md">
                                Editar Proyecto
                            </a>
                        </div>
                    </div>

                    <!-- calculos -->
                    
                    @php
                       
                        $inicioProyecto = \Carbon\Carbon::parse($proyecto->fecha_inicio)->startOfDay();
                        $finProyecto = $proyecto->fecha_fin ? \Carbon\Carbon::parse($proyecto->fecha_fin)->endOfDay() : null;

                        $pagosFiltrados = $proyecto->pagosEmpleados->filter(function ($pago) use ($inicioProyecto, $finProyecto) {
                            $fechaPago = \Carbon\Carbon::parse($pago->fecha_pago);
                            
                            if ($finProyecto) {
                                return $fechaPago->between($inicioProyecto, $finProyecto);
                            } else {
                                return $fechaPago->greaterThanOrEqualTo($inicioProyecto);
                            }
                        });

                       
                        $totalCostosExtras = $proyecto->costos->sum('monto');
                        $totalNomina = $pagosFiltrados->sum('monto'); 

                       
                        $costoTotalReal = $totalCostosExtras + $totalNomina;
                        $gananciaNeta = $proyecto->precio - $costoTotalReal;
                        $margenPorcentaje = $proyecto->precio > 0 ? ($gananciaNeta / $proyecto->precio) * 100 : 0;
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        
                        
                        <div class="lg:col-span-2 bg-slate-50 p-5 rounded-xl border border-slate-200 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-indigo-500"></i> Información General
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Estatus</p>
                                    <p class="font-semibold text-gray-800">
                                        {{ $proyecto->estatusProyecto->nombre ?? 'Desconocido' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Periodo del Proyecto</p>
                                    <p class="font-semibold text-gray-800">
                                        {{ $inicioProyecto->format('d/m/Y') }} 
                                        al 
                                        {{ $proyecto->fecha_fin ? $finProyecto->format('d/m/Y') : 'Actualidad' }}
                                    </p>
                                </div>
                                
                                
                                <div class="md:col-span-2 border-t border-slate-200 pt-3 mt-2">
                                    <p class="text-sm text-gray-500 mb-2">Equipo Asignado</p>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($proyecto->empleados as $empleado)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $empleado->nombre }} {{ $empleado->apellido_paterno }}
                                            </span>
                                        @empty
                                            <span class="text-gray-400 italic text-sm">No hay empleados asignados.</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Descripción</p>
                                    <p class="text-gray-700 bg-white p-3 rounded border border-slate-200 mt-1 text-sm">
                                        {{ $proyecto->descripcion ?? 'Sin descripción registrada.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border-2 {{ $gananciaNeta >= 0 ? 'border-green-100' : 'border-red-100' }} shadow-md">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-coins mr-2 text-yellow-500"></i> Balance Financiero
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Precio Venta:</span>
                                    <span class="text-xl font-bold text-indigo-700">${{ number_format($proyecto->precio, 2) }}</span>
                                </div>
                                <div class="border-t border-dashed border-slate-300"></div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">(-) Costos Extras:</span>
                                    <span class="font-medium text-red-500">${{ number_format($totalCostosExtras, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">(-) Nómina (Periodo):</span>
                                    <span class="font-medium text-red-500">${{ number_format($totalNomina, 2) }}</span>
                                </div>
                                
                                <div class="border-t border-slate-300 pt-2 mt-2">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-bold text-gray-600">Costo Total Real:</span>
                                        <span class="font-bold text-gray-800">${{ number_format($costoTotalReal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="font-bold text-gray-700">Ganancia:</span>
                                        <span class="text-2xl font-black {{ $gananciaNeta >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ${{ number_format($gananciaNeta, 2) }}
                                        </span>
                                    </div>
                                    <div class="text-right text-xs text-gray-400 mt-1">
                                        Margen: {{ number_format($margenPorcentaje, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <div>
                            <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-orange-400 mr-2"></span>
                                Costos Extras (Materiales, Servicios)
                            </h4>
                            <div class="overflow-hidden border border-slate-200 rounded-lg shadow-sm">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3">Concepto</th>
                                            <th class="px-4 py-3 text-right">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @forelse ($proyecto->costos as $costo)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-2 font-medium text-gray-900">{{ $costo->concepto }}</td>
                                                <td class="px-4 py-2 text-right font-medium text-red-600">-${{ number_format($costo->monto, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="px-4 py-4 text-center text-slate-400 italic">Sin costos extras registrados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-slate-50 font-semibold text-gray-900">
                                        <tr>
                                            <td class="px-4 py-2 text-right">Total Extras:</td>
                                            <td class="px-4 py-2 text-right text-red-700">-${{ number_format($totalCostosExtras, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                                Nómina (Pagos dentro del periodo)
                            </h4>
                            <div class="overflow-hidden border border-slate-200 rounded-lg shadow-sm">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-slate-100">
                                        <tr>
                                            <th class="px-4 py-3">Fecha</th>
                                            <th class="px-4 py-3">Empleado</th>
                                            <th class="px-4 py-3 text-right">Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                     
                                        @forelse ($pagosFiltrados->sortByDesc('fecha_pago') as $pago)
                                            <tr class="hover:bg-slate-50 transition-colors">
                                                <td class="px-4 py-2 whitespace-nowrap text-xs">
                                                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-2 font-medium text-gray-900">
                                                    {{ $pago->empleado->nombre ?? 'Ex-Empleado' }} {{ $pago->empleado->apellido_paterno ?? '' }}
                                                    <div class="text-xs text-gray-400 font-normal truncate max-w-[150px]" title="{{ $pago->descripcion }}">
                                                        {{ $pago->descripcion }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 text-right font-medium text-red-600">
                                                    -${{ number_format($pago->monto, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-4 text-center text-slate-400 italic">
                                                    No hay pagos registrados en este periodo de tiempo.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-slate-50 font-semibold text-gray-900">
                                        <tr>
                                            <td colspan="2" class="px-4 py-2 text-right">Total Nómina (Periodo):</td>
                                            <td class="px-4 py-2 text-right text-red-700">-${{ number_format($totalNomina, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>