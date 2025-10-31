<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Nómina - Lista de Pagos</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="">
        <div class="table-container">
            <div class="table-header">
                <h1 class="page-title">Nómina - Pagos a Empleados</h1>
                <a href="{{ route('pagos.create') }}" class="btn btn-primary">Nuevo Pago</a>
            </div>

            <!-- Filtros de Reporte -->
            <div class="filtros-container">
                <form action="{{ route('pagos.lista') }}" method="GET" class="filtros-form">
                    <div class="filtros-grid">
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

                        <div class="filtro-group">
                            <label for="fecha_pago" class="filtro-label">Fecha de Pago:</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="filtro-input" 
                                   value="{{ request('fecha_pago') }}">
                        </div>

                        <div class="filtro-group">
                            <label for="empleado" class="filtro-label">Empleado:</label>
                            <select name="empleado" id="empleado" class="filtro-select">
                                <option value="">Todos</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" {{ request('empleado') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->nombre }} {{ $empleado->apaterno }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filtro-actions">
                            <button type="submit" class="btn btn-filter">Filtrar</button>
                            <a href="{{ route('pagos.lista') }}" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>

            @if($pagos->isEmpty())
                <div class="empty-state">
                    <p>No hay pagos registrados.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                             
                                <th>Empleado</th>
                                <th>Monto</th>
                                <th>Fecha de Pago</th>
                                <th>Descripción</th>
                                <th>Proyecto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pagos as $pago)
                            <tr class="table-row">
                                <td>
                                    <strong>{{ $pago->empleado->nombre }} {{ $pago->empleado->apaterno }}</strong>
                                </td>
                                <td>${{ number_format($pago->monto, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                <td>{{ $pago->descripcion }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $pago->proyecto->nombre ?? 'No asignado' }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('pagos.editar', $pago->id) }}" class="btn-action btn-edit">Editar</a>
                                        <form action="{{ route('pagos.eliminar', $pago->id) }}" method="POST" class="action-form" id="form-eliminar-{{ $pago->id }}">
                                            @csrf
                                            @method('DELETE')
                                            {{-- <button type="button" class="btn-action btn-delete" onclick="confirmarEliminar({{ $pago->id }}, '{{ $pago->descripcion }}')">
                                                Eliminar
                                            </button> --}}
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="table-footer">
                    <div class="total-pagos">
                        <strong>Total de pagos: ${{ number_format($pagos->sum('monto'), 2) }}</strong>
                        <span class="pagos-count">({{ $pagos->count() }} registros)</span>
                    </div>
                </div>
            @endif
        </div>
    </div>



    <script>
        // Mostrar alertas desde session de Laravel
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

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