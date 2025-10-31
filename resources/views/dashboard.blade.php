<x-app-layout>
    {{-- Librerías CSS y JS --}}
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                
                events: calendarEvents,
                eventClick: function(info) {
                    const event = info.event;
                    const type = event.extendedProps.type;
                    const description = event.extendedProps.description;
                    const link = event.extendedProps.link;

                    const date = new Date(event.start);
                    const options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false };
                    const formattedDate = new Intl.DateTimeFormat('es-ES', options).format(date);

                    let title = '';
                    let htmlContent = '';

                    if (type === 'proyecto') {
                        title = 'Detalles del Proyecto';
                        htmlContent = `
                            <p><strong>Título:</strong> ${event.title}</p>
                            <p><strong>Descripción:</strong> ${description}</p>
                            <p><strong>Fecha:</strong> ${formattedDate}</p>
                            <p><a href="${link}" class="btn btn-primary">Ver Proyecto</a></p>
                        `;
                    } else if (type === 'pago') {
                        title = 'Detalles del Pago';
                        htmlContent = `
                            <p><strong>Título:</strong> ${event.title}</p>
                            <p><strong>Descripción:</strong> ${description}</p>
                            <p><strong>Fecha de Pago:</strong> ${formattedDate}</p>
                            ${link ? `<p><a href="${link}" class="btn btn-primary">Ver Detalles del Pago</a></p>` : ''}
                        `;
                    } else if (type === 'tarea') {
                        title = 'Detalles de la Tarea';
                        htmlContent = `
                            <p><strong>Título:</strong> ${event.title}</p>
                            <p><strong>Descripción:</strong> ${description}</p>
                            <p><strong>Fecha de Vencimiento:</strong> ${formattedDate}</p>
                            <p><a href="${link}" class="btn btn-primary">Ver Tarea</a></p>
                        `;
                    }

                    Swal.fire({
                        title: title,
                        html: htmlContent,
                        icon: 'info',
                        confirmButtonText: 'Cerrar'
                    });
                }
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

        {{-- 3 & 4. Alertas y Actividades Próximas Combinadas --}}
        <div class="dashboard-section alerts-and-upcoming-container">
            <div class="alerts-section">
                <h3 class="section-title">Alertas de Tareas Urgentes</h3>
                
                @forelse ($tareasUrgentes as $tarea)
                    <div class="alert-item">
                        <span class="alert-icon orange"></span>
                        <p>Tarea por vencer: <strong>{{ $tarea->titulo }}</strong> (Vence en {{ Carbon\Carbon::parse($tarea->fecha_fin)->locale('es')->diffForHumans() }}).</p>
                        <a href="{{ route('tareas.index') }}" class="btn-action">Ver Tarea</a>
                    </div>
                @empty
                    <div class="alert-item">
                        <span class="alert-icon" style="background-color: #28a745;"></span>
                        <p>No tienes alertas de tareas urgentes. ¡Buen trabajo!</p>
                    </div>
                @endforelse
            </div>

            <div class="upcoming-activities-section">
                <h3 class="section-title">Actividades Próximas</h3>
                @forelse ($actividadesProximas as $actividad)
                    <div class="alert-item activity-item" data-type="{{ $actividad['tipo'] }}" data-description="{{ $actividad['descripcion'] }}" data-fecha="{{ Carbon\Carbon::parse($actividad['fecha'])->format('d/m/Y H:i') }}">
                        <span class="alert-icon {{ $actividad['tipo'] == 'tarea' ? 'blue' : 'orange' }}"></span>
                        <p>{{ $actividad['descripcion'] }} (Vence en {{ Carbon\Carbon::parse($actividad['fecha'])->locale('es')->diffForHumans() }}).</p>
                    </div>
                @empty
                    <div class="alert-item">
                        <span class="alert-icon" style="background-color: #28a745;"></span>
                        <p>No hay actividades próximas.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <script>
            document.querySelectorAll('.activity-item').forEach(item => {
                item.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const description = this.dataset.description;
                    const fecha = this.dataset.fecha;
                    const link = this.dataset.link;

                    let title = '';
                    let htmlContent = '';

                    if (type === 'tarea') {
                        title = 'Detalles de la Tarea';
                        htmlContent = `
                            <p><strong>Descripción:</strong> ${description}</p>
                            <p><strong>Fecha de Vencimiento:</strong> ${fecha}</p>
                            <p><a href="${link}" class="btn btn-primary">Ver Tarea Completa</a></p>
                        `;
                    } else if (type === 'pago') {
                        title = 'Detalles del Pago';
                        htmlContent = `
                            <p><strong>Descripción:</strong> ${description}</p>
                            <p><strong>Fecha de Pago:</strong> ${fecha}</p>
                            <p><a href="${link}" class="btn btn-primary">Ver Detalles del Pago</a></p>
                        `;
                    }

                    Swal.fire({
                        title: title,
                        html: htmlContent,
                        icon: 'info',
                        confirmButtonText: 'Cerrar'
                    });
                });
            });
        </script>

        
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