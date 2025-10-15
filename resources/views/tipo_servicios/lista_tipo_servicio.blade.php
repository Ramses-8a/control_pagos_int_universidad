<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de tipos servicios</title>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Lista de tipos de servicio</h1>
        <a href="{{ route('tipo_servicios.crear') }}" class="btn btn-primary mb-3">Crear Nuevo Tipo</a>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tipos as $tipo)
                <tr>
                    <td>{{ $tipo->nombre }}</td>
                    <td>
                        <span class="badge {{ $tipo->estatus == 'activo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $tipo->estatus }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('tipo_servicios.editar', $tipo->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('tipo_servicios.eliminar', $tipo->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay tipos de servicio registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>