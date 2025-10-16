<!DOCTYPE html>
<html>
<head>
    <title>Lista de Puestos</title>
</head>
<body>
    <h1>Lista de Puestos</h1>

    <a href="{{ route('puestos.crear') }}"> Nuevo Puesto</a>

    @if (session('success'))
        <div style="color: green; margin: 10px 0;">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div style="color: red; margin: 10px 0;">{{ session('error') }}</div>
    @endif

    @if($puestos->isEmpty())
        <p>No hay puestos registrados.</p>
    @else
        <ul>
            @foreach($puestos as $puesto)
            <li>
                <strong>{{ $puesto->nombre }}</strong>
                <br>
                Descripción: {{ $puesto->descripcion ?? 'Sin descripción' }}
                <br>
                Estatus: 
                <span style="color: {{ $puesto->estatus == 'activo' ? 'green' : 'red' }};">
                    {{ $puesto->estatus }}
                </span>
                <br>
                Empleados: {{ $puesto->empleados->count() }}
                <br>
                <small>
                    <a href="{{ route('puestos.editar', $puesto->id) }}">Editar</a> | 
                    <form action="{{ route('puestos.eliminar', $puesto->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                         <button type="submit" onclick="return confirm('¿Estás seguro de desactivar este puesto?')">Desactivar</button>
                    </form>
                </small>
            </li>
            <hr>
            @endforeach
        </ul>
    @endif
</body>
</html>