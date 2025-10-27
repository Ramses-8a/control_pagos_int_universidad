<x-app-layout>
    {{-- Librerías CSS y JS --}}
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Obtenemos los datos del calendario desde el controlador de Laravel
        const calendarEvents = @json($eventos);
        
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- ✅ INICIO DE LA LÓGICA RESPONSIVA ---
            var calendarEl = document.getElementById('calendar');
            
            let initialViewSetting = 'dayGridMonth';
            let headerToolbarSetting = {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            };

            // Si la pantalla es pequeña (móvil)
            if (window.innerWidth < 768) {
                initialViewSetting = 'listWeek'; // Usar vista de lista
                headerToolbarSetting = {
                    left: 'prev,next', // Botones más simples
                    center: 'title',
                    right: 'listWeek,dayGridMonth' // Opciones de vista reducidas
                };
            }
            // --- ✅ FIN DE LA LÓGICA RESPONSIVA ---


            // --- INICIALIZACIÓN DE FULLCALENDAR (Ahora responsivo) ---
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                
                // ✅ ¡CAMBIOS RESPONSIVOS APLICADOS!
                initialView: initialViewSetting,
                headerToolbar: headerToolbarSetting,
                height: 'auto', // Dejamos que el calendario decida su altura
                
                events: calendarEvents 
            });
            calendar.render();

            // --- INICIALIZACIÓN DEL GRÁFICO CIRCULAR (Sin cambios) ---
            const ctx = document.getElementById('accountsChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cobrado', 'Pendiente'],
                    datasets: [{
                        label: 'Estado de Cuentas',
                        data: [{{ $porcentajeCobrado }}, {{ $porcentajePendiente }}],
                        backgroundColor: [
                            '#28a745', // Verde para 'Cobrado'
                            '#e9ecef'  // Gris claro para 'Pendiente'
                        ],
                        borderColor: [
                            '#ffffff'
                        ],
                        borderWidth: 2,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        });
    </script>

    <div class="dashboard-grid-container">

        {{-- 1. Sección de Resumen (Dinámico) --}}
        <div class="dashboard-section summary-section">
            <h3 class="section-title">Resumen</h3>
            <div class="summary-cards">
                <div class="card">
                    <p class="card-title">INGRESOS COBRADOS</p>
                    <p class="card-amount color-green">${{ number_format($ingresosCobrados, 2) }}</p>
                    <p class="card-detail">(Proyectos completados)</p>
                </div>
                <div class="card">
                    <p class="card-title">GASTOS PAGADOS</p>
                    <p class="card-amount color-orange">${{ number_format($gastosPagados, 2) }}</p>
                    <p class="card-detail">(Nómina y otros)</p>
                </div>
                <div class="card">
                    <p class="card-title">GANANCIA NETA</p>
                    <p class="card-amount color-blue">${{ number_format($gananciaNeta, 2) }}</p>
                </div>
                <div class="card chart-card">
                    <p class="card-title">Estado de Cuentas por Cobrar</p>
                    <div class="chart-container">
                        <canvas id="accountsChart"></canvas>
                    </div>
                    <p class="card-detail">
                        <span class="legend-dot green"></span>
                        Cobrado ({{ number_format($porcentajeCobrado, 0) }}%)
                    </p>
                </div>
            </div>
        </div>

        {{-- 2. Calendario de Vencimientos y Actividades (Dinámico) --}}
        <div class="dashboard-section calendar-section">
            <h3 class="section-title">Calendario de Vencimientos y Actividades</h3>
            <div id="calendar"></div>
        </div>

        {{-- 3. Alertas y Acciones Urgentes (Dinámico) --}}
        <div class="dashboard-section alerts-section">
            <h3 class="section-title">Alertas y Acciones Urgentes</h3>
            
            @forelse ($tareasUrgentes as $tarea)
                <div class="alert-item">
                    <span class="alert-icon orange"></span>
                    <p>Tarea por vencer: <strong>{{ $tarea->titulo }}</strong> (Vence {{ $tarea->fecha_fin->diffForHumans() }}).</p>
                    <a href="{{ route('tareas.index') }}" class="btn-action">Ver Tarea</a>
                </div>
            @empty
                <div class="alert-item">
                    <span class="alert-icon" style="background-color: #28a745;"></span>
                    <p>No tienes alertas urgentes. ¡Buen trabajo!</p>
                </div>
            @endforelse
        </div>

        {{-- 4. Pendientes y Actividades Próximas (Dinámico) --}}
        <div class="dashboard-section upcoming-activities-section">
            <h3 class="section-title">Pendientes y Actividades Próximas</h3>
            <ul>
                @forelse ($proximasActividades as $tarea)
                    <li>
                        <span class="activity-bullet blue"></span>
                        <div>
                            <p>{{ $tarea->titulo }}</p>
                            <p class="activity-time">{{ $tarea->fecha_fin->format('d \d\e F, Y') }}</p>
                        </div>
                    </li>
                @empty
                     <li>
                        <span class="activity-bullet"></span>
                        <div>
                            <p>No hay más actividades programadas.</p>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        
        {{-- 5. Salud de Proyectos Activos (Dinámico) --}}
        <div class="dashboard-section projects-section">
            <h3 class="section-title">Salud de Proyectos Activos</h3>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Proyecto</th>
                            <th>Rentabilidad</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($proyectosActivos as $proyecto)
                            <tr>
                                <td>{{ $proyecto->descripcion }}</td>
                                
                                @php
                                    $rentabilidad = ($proyecto->precio > 0) ? (($proyecto->precio - $proyecto->costo) / $proyecto->precio) * 100 : 0;
                                    $profitClass = 'profit-neutral';
                                    if ($rentabilidad > 15) $profitClass = 'profit-positive';
                                    if ($rentabilidad < 0) $profitClass = 'profit-negative';
                                @endphp
                                <td class="{{ $profitClass }}">{{ number_format($rentabilidad, 0) }}%</td>
                                
                                @php
                                    $statusClass = 'status-progress'; // Default
                                    $statusNombre = optional($proyecto->estatusProyecto)->nombre ?? 'En Progreso';
                                    
                                    switch(strtolower($statusNombre)) {
                                        case 'en progreso': $statusClass = 'status-progress'; break;
                                        case 'retrasado': $statusClass = 'status-delayed'; break;
                                        case 'en pausa': $statusClass = 'status-paused'; break;
                                    }
                                @endphp
                                <td><span class="status-tag {{ $statusClass }}">{{ $statusNombre }}</span></td>
                                
                                <td>
                                    <a href="{{ route('proyectos.show', $proyecto->id) }}" class="btn-manage">Gestionar</a>
                                </td>
                            </tr>
                        @empty
                             <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">No hay proyectos activos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>