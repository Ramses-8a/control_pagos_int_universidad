<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Empleados</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="">
        <div class="table-container">
            <div class="table-header">
                <h1 class="page-title">Lista de empleados</h1>
                <a href="{{ route('empleados.create') }}" class="btn btn-primary"> Nuevo Empleado</a>
            </div>

            @if($empleados->isEmpty())
                <div class="empty-state">
                    <p>No hay empleados registrados.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre Completo</th>  
                                <th>Puesto</th>           
                                <th>Correo</th>            
                                <th>Periodo de Pago</th>
                                <th>Acciones</th>         
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleados as $empleado)
                            <tr class="table-row">
                                <td>
                                    <strong>{{ $empleado->nombre }} {{ $empleado->apaterno }} {{ $empleado->amaterno }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $empleado->puesto->nombre ?? 'No asignado' }}</span>
                                </td>
                                <td>{{ $empleado->correo }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $empleado->periodoPago->nombre ?? 'No asignado' }}</span>
                                </td>
                            
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('empleados.historial', $empleado->id) }}" class="btn-action btn-history" title="Ver historial de pagos">
                                            Historial
                                        </a>
                                        <a href="{{ route('empleados.editar', $empleado->id) }}" class="btn-action btn-edit">Editar</a>
                                        
                                        @if($empleado->estatus == '1')
                                            <form action="{{ route('empleados.eliminar', $empleado->id) }}" method="POST" class="action-form" id="form-desactivar-{{ $empleado->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-action btn-delete" onclick="confirmarDesactivar({{ $empleado->id }}, '{{ $empleado->nombre }} {{ $empleado->apaterno }}')">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('empleados.activate', $empleado->id) }}" method="POST" class="action-form" id="form-activar-{{ $empleado->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn-action btn-success" onclick="confirmarActivar({{ $empleado->id }}, '{{ $empleado->nombre }} {{ $empleado->apaterno }}')">
                                                    Reactivar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <style>

    </style>

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

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                confirmButtonText: 'Entendido'
            });
        @endif

        // Confirmación para desactivar empleado
        function confirmarDesactivar(id, nombre) {
            Swal.fire({
                title: '¿Desactivar empleado?',
                text: `¿Estás seguro de desactivar a "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Desactivando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Enviar formulario
                    document.getElementById(`form-desactivar-${id}`).submit();
                }
            });
        }

        // Confirmación para activar empleado
        function confirmarActivar(id, nombre) {
            Swal.fire({
                title: '¿Reactivar empleado?',
                text: `¿Estás seguro de reactivar a "${nombre}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, reactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Reactivando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Enviar formulario
                    document.getElementById(`form-activar-${id}`).submit();
                }
            });
        }
    </script>
</body>
</html>
</x-app-layout>