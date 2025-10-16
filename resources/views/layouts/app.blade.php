<x-app-layout>
    {{-- Librerías JS para Calendario y Gráficos --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- INICIALIZACIÓN DE FULLCALENDAR ---
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                height: '100%',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: [
                    { title: 'Pago a Empleado Y', start: '2025-10-18', color: '#ff9f40' },
                    { title: 'Factura #2025-087 Vence', start: '2025-10-10', color: '#ea5555' },
                    { title: 'Kickoff Proyecto Alfa', start: '2025-10-15T11:00:00', color: '#0052cc' }
                ]
            });
            calendar.render();

            // --- INICIALIZACIÓN DEL GRÁFICO CIRCULAR ---
            const ctx = document.getElementById('accountsChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cobrado', 'Pendiente'],
                    datasets: [{
                        data: [25, 75], // 25% cobrado, 75% pendiente
                        backgroundColor: ['#28a745', '#e9ecef'],
                        borderColor: ['#ffffff'],
                        borderWidth: 2,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } }
                }
            });
        });
    </script>

    <div class="dashboard-grid-container">
        <div class="summary-section">
            <h3 class="section-title">Resumen</h3>
            <div class="summary-cards">
                <div class="card">
                    <p class="card-title">INGRESOS COBRADOS</p>
                    <p class="card-amount color-green">$15,200.00</p>
                    <p class="card-detail">vs. $12,500.00 el mes pasado</p>
                </div>
                <div class="card">
                    <p class="card-title">GASTOS PAGADOS</p>
                    <p class="card-amount color-orange">$8,300.00</p>
                </div>
                <div class="card">
                    <p class="card-title">GANANCIA NETA</p>
                    <p class="card-amount color-blue">$6,900.00</p>
                </div>
                <div class="card chart-card">
                    <p class="card-title">Estado de Cuentas por Cobrar</p>
                    <div class="chart-container"><canvas id="accountsChart"></canvas></div>
                    <p class="card-detail"><span class="legend-dot green"></span>Cobrado (25%)</p>
                </div>
            </div>
        </div>

        <div class="dashboard-section calendar-section">
            <h3 class="section-title">Calendario de Vencimientos y Actividades</h3>
            <div id="calendar"></div>
        </div>

        <div class="alerts-section">
             <h3 class="section-title">Alertas y Acciones Urgentes</h3>
             <div class="alert-item">
                <span class="alert-icon red"></span>
                <p>Factura #2025-087 de "Cliente X" tiene 5 días de vencida.</p>
                <button class="btn-action">Enviar Recordatorio</button>
            </div>
            <div class="alert-item">
                <span class="alert-icon orange"></span>
                <p>El pago de nómina para "Empleado Y" debe realizarse en 3 días.</p>
                <button class="btn-action">Procesar Pago</button>
            </div>
        </div>

        <div class="upcoming-activities-section">
            <h3 class="section-title">Pendientes y Actividades Próximas</h3>
            <ul>
                <li>
                    <span class="activity-bullet blue"></span>
                    <div>
                        <p>Reunión de kickoff - Proyecto Alfa</p>
                        <p class="activity-time">Hoy a las 11:00 AM</p>
                    </div>
                </li>
                <li>
                    <span class="activity-bullet"></span>
                    <div>
                        <p>Entrega de borrador a Cliente Z</p>
                        <p class="activity-time">17 de Octubre, 2025</p>
                    </div>
                </li>
            </ul>
        </div>
        
        <div class="dashboard-section projects-section">
            <h3 class="section-title">Salud de Proyectos Activos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Proyecto</th><th>Cliente</th><th>Rentabilidad</th><th>Estado</th><th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Diseño Web Corp</td><td>Cliente Corp</td><td class="profit-positive">+19%</td>
                        <td><span class="status-tag status-delayed">Retrasado</span></td>
                        <td><button class="btn-manage">Gestionar</button></td>
                    </tr>
                    <tr>
                        <td>App Móvil Corp</td><td>Mobile Inc.</td><td class="profit-neutral">+2%</td>
                        <td><span class="status-tag status-progress">En Progreso</span></td>
                        <td><button class="btn-manage">Gestionar</button></td>
                    </tr>
                    <tr>
                        <td>App Móvil UX</td><td>Creative Solutions</td><td class="profit-negative">-25%</td>
                        <td><span class="status-tag status-paused">En Pausa</span></td>
                        <td><button class="btn-manage">Gestionar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>