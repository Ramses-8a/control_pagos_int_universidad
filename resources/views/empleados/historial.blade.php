<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Historial de Pagos - {{ $empleado->nombre }} {{ $empleado->apaterno }}</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="">
        <div class="table-container">
            <div class="table-header">
                <div class="header-info">
                    <h1 class="page-title">Historial de Pagos</h1>
                    <div class="empleado-info">
                        <h3>{{ $empleado->nombre }} {{ $empleado->apaterno }} {{ $empleado->amaterno }}</h3>
                        <p><strong>Puesto:</strong> {{ $empleado->puesto->nombre ?? 'No asignado' }} | 
                           <strong>Correo:</strong> {{ $empleado->correo }} | 
                           <strong>Periodo Pago:</strong> {{ $empleado->periodoPago->nombre ?? 'No asignado' }}</p>
                    </div>
                </div>
                <a href="{{ route('empleados.lista') }}" class="btn btn-secondary">Volver a Empleados</a>
            </div>

            <!-- Filtros del Historial -->
            <div class="filtros-container">
                <form action="{{ route('empleados.historial', $empleado->id) }}" method="GET" class="filtros-form">
                    <div class="filtros-grid">
                        <div class="filtro-group">
                            <label for="fecha_pago" class="filtro-label">Fecha de Pago:</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="filtro-input" 
                                   value="{{ request('fecha_pago') }}">
                        </div>

                        <div class="filtro-group">
                            <label for="proyecto" class="filtro-label">Proyecto:</label>
                            <select name="proyecto" id="proyecto" class="filtro-select">
                                <option value="">Todos</option>
                                @foreach($proyectos as $proyecto)
                                    <option value="{{ $proyecto->id }}" {{ request('proyecto') == $proyecto->id ? 'selected' : '' }}>
                                        {{ $proyecto->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filtro-actions">
                            <button type="submit" class="btn btn-filter">Filtrar</button>
                            <a href="{{ route('empleados.historial', $empleado->id) }}" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>

            @if($pagos->isEmpty())
                <div class="empty-state">
                    <p>No hay pagos registrados para este empleado.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Monto</th>
                                <th>Fecha de Pago</th>
                                <th>Descripción</th>
                                <th>Proyecto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagos as $pago)
                            <tr class="table-row">
                                <td>${{ number_format($pago->monto, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                <td>{{ $pago->descripcion }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $pago->proyecto->nombre ?? 'No asignado' }}</span>
                                </td>
                                <td>
                                    
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="table-footer">
                    <div class="total-pagos">
                        <strong>Total pagado: ${{ number_format($pagos->sum('monto'), 2) }}</strong>
                        <span class="pagos-count">({{ $pagos->count() }} pagos)</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>

    </style>

    <script>
        // Confirmación para eliminar pago
        function confirmarEliminar(id, descripcion) {
            Swal.fire({
                title: '¿Eliminar pago?',
                text: `¿Estás seguro de eliminar el pago "${descripcion}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Eliminando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Enviar formulario
                    document.getElementById(`form-eliminar-${id}`).submit();
                }
            });
        }
    </script>
</body>
</html>
</x-app-layout>