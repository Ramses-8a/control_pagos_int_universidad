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
                   
                    <a href="#">Editar</a> | 
                    <form action="#" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Desactivar</button>
                    </form>
                </small>
            </li>
            <hr>
            @endforeach
        </ul>
    @endif
</body>
</html>