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
                                     @foreach ($estatusOptions as $key => $value)
                                         <option value="{{ $key }}" {{ request('estatus') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                     @endforeach
                                 </select>
                             </div>
                             <div class="col-span-full md:col-span-1 hidden">
                                 <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full">
                                     Filtrar Reporte
                                 </button>
                             </div>
                         </form>
                     </div>
 
                     @if(count($finalReportData['proyectos']) > 0)
                         <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                             <div class="bg-blue-100 p-4 rounded-md shadow-md">
                                 <h4 class="font-semibold text-blue-800">Total Gastos</h4>
                                 <p class="text-2xl text-blue-900">${{ number_format($finalReportData['total_gastos'], 2) }}</p>
                             </div>
                             <div class="bg-green-100 p-4 rounded-md shadow-md">
                                 <h4 class="font-semibold text-green-800">Total Ganancias</h4>
                                 <p class="text-2xl text-green-900">${{ number_format($finalReportData['total_ganancias'], 2) }}</p>
                             </div>
                             <div class="bg-red-100 p-4 rounded-md shadow-md">
                                 <h4 class="font-semibold text-red-800">Total Pérdidas</h4>
                                 <p class="text-2xl text-red-900">${{ number_format($finalReportData['total_perdidas'], 2) }}</p>
                             </div>
                         </div>
 
                         <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
                             <h3 class="text-lg font-medium text-gray-900 mb-4">Gráfico de Proyectos</h3>
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
                                         <p class="text-gray-700 text-sm">Gastos: <span class="font-semibold text-red-600">${{ number_format($report['gastos'], 2) }}</span></p>
                                         <p class="text-gray-700 text-sm">Ganancias: <span class="font-semibold text-green-600">${{ number_format($report['ganancias'], 2) }}</span></p>
                                         <p class="text-gray-700 text-sm">Pérdidas: <span class="font-semibold {{ $report['perdidas'] < 0 ? 'text-green-600' : 'text-red-600' }}">${{ number_format($report['perdidas'], 2) }}</span></p>
                                     </div>
                                 @endforeach
                             </div>
                         </div>
 
                         <script>
                             document.addEventListener('DOMContentLoaded', function() {
                                 const ctx = document.getElementById('projectsChart').getContext('2d');
                                 const projectsChart = new Chart(ctx, {
                                     type: 'pie',
                                     data: {
                                         labels: ['Gastos', 'Ganancias', 'Pérdidas'],
                                         datasets: [
                                             {
                                                 label: 'Valores',
                                                 data: [
                                                     @json($finalReportData['total_gastos']),
                                                     @json($finalReportData['total_ganancias']),
                                                     @json(abs($finalReportData['total_perdidas']))
                                                 ],
                                                 backgroundColor: [
                                                     'rgba(255, 99, 132, 0.6)',
                                                     'rgba(75, 192, 192, 0.6)',
                                                     'rgba(255, 206, 86, 0.6)'
                                                 ],
                                                 borderColor: [
                                                     'rgba(255, 99, 132, 1)',
                                                     'rgba(75, 192, 192, 1)',
                                                     'rgba(255, 206, 86, 1)'
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
                                                 text: 'Resumen Financiero General'
                                             }
                                         }
                                     }
                                 });
                             });
                         </script>
                     @else
                         <p class="mt-8">No se encontraron resultados para los filtros seleccionados.</p>
                     @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>