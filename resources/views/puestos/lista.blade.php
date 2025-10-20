<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Puestos</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="table-header">
                <h1 class="page-title">Lista de puestos</h1>
                <a href="{{ route('puestos.crear') }}" class="btn btn-primary">Nuevo Puesto</a>
            </div>

            @if($puestos->isEmpty())
                <div class="empty-state">
                    <p>No hay puestos registrados.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($puestos as $puesto)
                            <tr class="table-row">
                                <td>{{ $puesto->nombre }}</td>
                                <td>{{ $puesto->descripcion ?? 'Sin descripción' }}</td>
                               
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('puestos.editar', $puesto->id) }}" class="btn-action btn-edit">Editar</a>
                                        
                                        @if($puesto->estatus == '1')
                                            <form action="{{ route('puestos.eliminar', $puesto->id) }}" method="POST" class="action-form" id="form-desactivar-{{ $puesto->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-action btn-delete" onclick="confirmarDesactivar({{ $puesto->id }}, '{{ $puesto->nombre }}')">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('puestos.activate', $puesto->id) }}" method="POST" class="action-form" id="form-activar-{{ $puesto->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn-action btn-success" onclick="confirmarActivar({{ $puesto->id }}, '{{ $puesto->nombre }}')">
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

    <script>
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
        function confirmarDesactivar(id, nombre) {
            Swal.fire({
                title: '¿Desactivar puesto?',
                text: `¿Estás seguro de desactivar el puesto "${nombre}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Desactivando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById(`form-desactivar-${id}`).submit();
                }
            });
        }
        function confirmarActivar(id, nombre) {
            Swal.fire({
                title: '¿Reactivar puesto?',
                text: `¿Estás seguro de reactivar el puesto "${nombre}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, reactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Reactivando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    document.getElementById(`form-activar-${id}`).submit();
                }
            });
        }
    </script>
</body>
</html>
</x-app-layout>