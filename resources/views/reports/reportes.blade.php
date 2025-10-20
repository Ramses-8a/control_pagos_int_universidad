<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes de Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                     <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                         <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros de Reporte</h3>
                         {{-- El formulario de filtros sigue igual --}}
                         <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                             <div>
                                 <label for="project_id" class="block text-sm font-medium text-gray-700">Proyecto:</label>
                                 <select name="project_id" id="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-1" onchange="this.form.submit()">
                                     <option value="">Todos</option>
                                     @foreach ($proyectos as $proyecto)
                                         <option value="{{ $proyecto->id }}" {{ request('project_id') == $proyecto->id ? 'selected' : '' }}>{{ $proyecto->nombre }}</option>
                                     @endforeach
                                 </select>
                             </div>
                             <div>
                                 <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha Inicio:</label>
                                 <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-1" onchange="this.form.submit()">
                             </div>
                             <div>
                                 <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha Fin:</label>
                                 <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-1" onchange="this.form.submit()">
                             </div>
                             <div>
                                 <label for="estatus" class="block text-sm font-medium text-gray-700">Estatus:</label>
                                 <select name="estatus" id="estatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-1" onchange="this.form.submit()">
                                     <option value="">Todos</option>
                                     @foreach ($estatusProyectos as $estatus)
                                         <option value="{{ $estatus->nombre }}" {{ request('estatus') == $estatus->nombre ? 'selected' : '' }}>{{ $estatus->nombre }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </form>
                     </div>

                     {{-- Usamos $finalReportData['proyectos'] para verificar si hay datos --}}
                     @if(count($finalReportData['proyectos']) > 0)
                         <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                             <div class="bg-red-100 p-4 rounded-md shadow-md">
                                 {{-- ETIQUETA ACTUALIZADA --}}
                                 <h4 class="font-semibold text-red-800">Total Gastos (Periodo)</h4>
                                 <p class="text-2xl text-red-900">${{ number_format($finalReportData['total_gastos'], 2) }}</p>
                             </div>
                             <div class="bg-green-100 p-4 rounded-md shadow-md">
                                 {{-- ETIQUETA ACTUALIZADA --}}
                                 <h4 class="font-semibold text-green-800">Total Ganancias (Proyectos)</h4>
                                 <p class="text-2xl text-green-900">${{ number_format($finalReportData['total_ganancias'], 2) }}</p>
                             </div>
                             <div class="{{ $finalReportData['total_perdidas'] >= 0 ? 'bg-blue-100' : 'bg-red-100' }} p-4 rounded-md shadow-md">
                                 <h4 class="font-semibold {{ $finalReportData['total_perdidas'] >= 0 ? 'text-blue-800' : 'text-red-800' }}">Balance Neto</h4>
                                 <p class="text-2xl {{ $finalReportData['total_perdidas'] >= 0 ? 'text-blue-900' : 'text-red-900' }}">${{ number_format($finalReportData['total_perdidas'], 2) }}</p>
                             </div>
                         </div>

                         <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
                             <h3 class="text-lg font-medium text-gray-900 mb-4">Gráfico Comparativo</h3>
                             <div class="flex justify-center items-center" style="height: 300px;">
                                 <canvas id="projectsChart" class="max-h-full max-w-full"></canvas>
                             </div>
                         </div>

                         <div class="mt-8">
                             <h3 class="text-lg font-medium text-gray-900 mb-4">Detalle por Proyecto</h3>
                             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                 @foreach($finalReportData['proyectos'] as $report)
                                     <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500">
                                         <h4 class="font-bold text-xl text-gray-800 mb-2">{{ $report['proyecto'] }}</h4>
                                         {{-- ETIQUETAS ACTUALIZADAS PARA MAYOR CLARIDAD --}}
                                         <p class="text-gray-700 text-sm">Gastos (Periodo): <span class="font-semibold text-red-600">${{ number_format($report['gastos'], 2) }}</span></p>
                                         <p class="text-gray-700 text-sm">Ganancia (Total): <span class="font-semibold text-green-600">${{ number_format($report['ganancias'], 2) }}</span></p>
                                         <p class="text-gray-700 text-sm">Balance Neto: 
                                             <span class="font-semibold {{ $report['perdidas'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                 ${{ number_format($report['perdidas'], 2) }}
                                             </span>
                                         </p>
                                     </div>
                                 @endforeach
                             </div>
                         </div>

                         {{-- SCRIPT DEL GRÁFICO ACTUALIZADO --}}
                         <script>
                             document.addEventListener('DOMContentLoaded', function() {
                                 const ctx = document.getElementById('projectsChart').getContext('2d');
                                 const projectsChart = new Chart(ctx, {
                                     type: 'pie', 
                                     data: {
                                         // ETIQUETAS ACTUALIZADAS
                                         labels: ['Total Gastos (Periodo)', 'Total Ganancias (Proyectos)'], 
                                         datasets: [
                                             {
                                                 label: 'Monto',
                                                 data: [
                                                     @json($finalReportData['total_gastos']),
                                                     @json($finalReportData['total_ganancias']),
                                                 ],
                                                 backgroundColor: [
                                                     'rgba(255, 99, 132, 0.6)', // Rojo para Gastos
                                                     'rgba(75, 192, 192, 0.6)', // Verde para Ganancias
                                                 ],
                                                 borderColor: [
                                                     'rgba(255, 99, 132, 1)',
                                                     'rgba(75, 192, 192, 1)',
                                                 ],
                                                 borderWidth: 1
                                             }
                                         ]
                                     },
                                     options: {
                                         responsive: true,
                                         maintainAspectRatio: false,
                                         plugins: {
                                             legend: {
                                                 position: 'right',
                                             },
                                             title: {
                                                 display: true,
                                                 // TÍTULO ACTUALIZADO
                                                 text: 'Gastos del Periodo vs. Ganancias Totales' 
                                             }
                                         }
                                     }
                                 });
                             });
                         </script>
                     @else
                         {{-- Mensaje si la consulta $query no devuelve proyectos --}}
                         <p class="mt-8 text-center text-gray-500">No se encontraron proyectos para los filtros seleccionados.</p>
                     @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>