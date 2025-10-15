<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de servicios</title>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Lista de servicios</h1>

        <a href="{{ route('servicios.crear') }}" class="btn btn-primary mb-3">Crear nuevo servicio</a>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Costo</th>
                    <th>Tipo de Servicio</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->nombre }}</td>
                    <td>${{ number_format($servicio->costo, 2) }}</td>
                    <td>{{ $servicio->tipoServicio->nombre }}</td>
                    <td>
                        <span class="badge {{ $servicio->estatus == 'activo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $servicio->estatus }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('servicios.editar', $servicio->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('servicios.eliminar', $servicio->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No hay servicios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>

</html>