<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/Dashboard.css') }}">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: [
                    // Aquí puedes añadir tus eventos dinámicamente
                    { title: 'Evento de prueba', start: '2024-03-10' },
                    { title: 'Otro evento', start: '2024-03-12', end: '2024-03-14' }
                ]
            });
            calendar.render();
        });
    </script>

    <div class="dashboard-container">
        <!-- Resumen Dabrades -->
        <div class="summary-section">
            <h3 class="section-title">Resumen</h3>
            <div class="summary-cards">
                <div class="card">
                    <p>INGRESOS COBRADOS</p>
                    <p class="amount">$15,200.00</p>
                    <p class="detail">w. 322,989.9 dmes pasado</p>
                </div>
                <div class="card">
                    <p>GASTOS PAGADOS</p>
                    <p class="amount">$8,300.00</p>
                </div>
                <div class="card">
                    <p>GANANCIA NETA</p>
                    <p class="amount">$6,900.00</p>
                </div>
                <div class="card chart-card">
                    <p>Estado de Cuentas por Cobrar</p>
                    <div class="chart-placeholder"></div>
                    <p class="detail">Cdenade (25% (672880)</p>
                </div>
            </div>
        </div>

        <div class="main-content-grid">
            <!-- Calendario de Vencimientos y Actividades -->
            <div class="calendar-section">
                <h3 class="section-title">Calendario de Vencimientos y Actividades</h3>

                    <div id="calendar"></div>
                </div>
            </div>

            <!-- Alertas y Acciones Urgentes -->
            <div class="alerts-section">
                <h3 class="section-title">Alertas y Acciones Urgentes</h3>
                <div class="alert-item">
                    <span class="alert-icon red"></span>
                    <p>Fedura $3025 087 de "Cliente X" tiene 5 dias de vencida.</p>
                    <button class="btn-action">Enviar Recordatorio</button>
                </div>
                <div class="alert-item">
                    <span class="alert-icon orange"></span>
                    <p>El pago do oómma pan Empleado Y debe rebe vallzza on d dica.</p>
                    <button class="btn-action">Procesar Pago</button>
                </div>
                <div class="alert-item">
                    <span class="alert-icon blue"></span>
                    <p>El payodo Cliolio Y time al precients ercoelido on an HPPL</p>
                    <button class="btn-action">Ver Informe</button>
                </div>
            </div>

            <!-- Salud de Proyectos Activos -->
            <div class="projects-section">
                <h3 class="section-title">Salud de Proyectos Activos</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Rayado</th>
                            <th>Cliente</th>
                            <th>% Completada</th>
                            <th>Rentabilidad</th>
                            <th>Estado</th>
                            <th>Gestión</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Dissina Web Corp</td>
                            <td></td>
                            <td class="profit-positive">+19%</td>
                            <td>Retrasado</td>
                            <td><button class="btn-manage">Retwrede</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>App Móvilb Corp</td>
                            <td></td>
                            <td></td>
                            <td>En Curso</td>
                            <td><button class="btn-manage">Rerronde</button></td>
                        </tr>
                        <tr>
                            <td>15</td>
                            <td>App Móvil UX</td>
                            <td>+28%</td>
                            <td></td>
                            <td>En Pausa</td>
                            <td><button class="btn-manage">be Fragede</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pendientes y Actividades Próximas -->
            <div class="upcoming-activities-section">
                <h3 class="section-title">Pendientes y Actividades Próximas</h3>
                <ul>
                    <li>
                        <span class="activity-bullet blue"></span>
                        <p>Reunión de kiocif - Prsyeta Alfa</p>
                        <p class="activity-time">Hoy a las 11:30 AM</p>
                    </li>
                    <li>
                        <span class="activity-bullet blue"></span>
                        <p>Rokatas prayizleca pars Novo Prosposto Masana</p>
                        <p class="activity-time">15 da Octubre</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
