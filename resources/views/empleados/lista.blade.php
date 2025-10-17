<!DOCTYPE html>
<html>
<head>
    <title>Lista de Empleados</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="table-header">
                <h1 class="page-title">Lista de Empleados</h1>
                <a href="{{ route('empleados.create') }}" class="btn btn-primary"> Nuevo Empleado</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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
                                <th>Email</th>            
                                <th>Teléfono</th>        
                                <th>Acciones</th>         
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empleados as $empleado)
                            <tr class="table-row">
                                <td>
                                    <strong>{{ $empleado->nombre }} {{ $empleado->apellidos }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $empleado->puesto->nombre ?? 'No asignado' }}</span>
                                </td>
                                <td>{{ $empleado->email }}</td>
                                <td>{{ $empleado->telefono ?? 'No especificado' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('empleados.editar', $empleado->id) }}" class="btn-action btn-edit">Editar</a>
                                        <form action="{{ route('empleados.eliminar', $empleado->id) }}" method="POST" class="action-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de desactivar este empleado?')">Desactivar</button>
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