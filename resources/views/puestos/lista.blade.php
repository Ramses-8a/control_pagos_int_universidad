<!DOCTYPE html>
<html>
<head>
    <title>Lista de Puestos</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="table-header">
                <h1 class="page-title">Lista de Puestos</h1>
                <a href="{{ route('puestos.crear') }}" class="btn btn-primary">Nuevo Puesto</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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
                                <th>Estatus</th>
                                <th>Empleados</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($puestos as $puesto)
                            <tr class="table-row">
                                <td class="table-cell">
                                    <strong>{{ $puesto->nombre }}</strong>
                                </td>
                                <td class="table-cell">
                                    {{ $puesto->descripcion ?? 'Sin descripción' }}
                                </td>
                                <td class="table-cell">
                                    <span class="badge {{ $puesto->estatus == 'activo' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $puesto->estatus }}
                                    </span>
                                </td>
                                <td class="table-cell">
                                    <span class="badge badge-info">{{ $puesto->empleados->count() }}</span>
                                </td>
                                <td class="table-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('puestos.editar', $puesto->id) }}" class="btn-action btn-edit">Editar</a>
                                        <form action="{{ route('puestos.eliminar', $puesto->id) }}" method="POST" class="action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de desactivar este puesto?')">Desactivar</button>
                                        </form>
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
</body>
</html>