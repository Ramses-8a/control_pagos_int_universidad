<!DOCTYPE html>
<html>
<head>
    <title>Lista de Empleados</title>
</head>
<body>
    <h1>Lista de Empleados</h1>

    <a href="{{ route('empleados.create') }}">Nuevo Empleado</a>

    @if (session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif

    @if($empleados->isEmpty())
        <p>No hay empleados registrados.</p>
    @else
        <ul>
            @foreach($empleados as $empleado)
            <li>
                <strong>{{ $empleado->nombre }} {{ $empleado->apellidos }}</strong>
                <br>
                Puesto: {{ $empleado->puesto->nombre ?? 'No asignado' }} 
                <br>
                Email: {{ $empleado->email }}
                <br>
                Teléfono: {{ $empleado->telefono ?? 'No especificado' }}
                <br>
               <small>
                <a href="{{ route('empleados.editar', $empleado->id) }}">Editar</a> | 
                <form action="{{ route('empleados.eliminar', $empleado->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Estás seguro de desactivar este empleado?')">Desactivar</button>
                </form>
                </small>
            <hr>
            @endforeach
        </ul>
    @endif
</body>
</html>