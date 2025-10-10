<!DOCTYPE html>
<html>
<head>
    <title>Registrar Empleado</title>
</head>
<body>
    <h1>Registrar Nuevo Empleado</h1>

    @if ($errors->any())
        <div>
            <strong>Errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <form action="{{ route('empleados.store') }}" method="POST">
        @csrf
        
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
        </div>

        <div>
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" required>
        </div>

        <div>
            <label for="puesto_id">Puesto:</label>
            <select name="puesto_id" id="puesto_id" required>
                <option value="">Seleccione un puesto</option>
                @foreach($puestos as $puesto)
                    <option value="{{ $puesto->id }}" {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                        {{ $puesto->nombre }}
                    </option>
                @endforeach
            </select>
            @error('puesto_id') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}">
        </div>

        <button type="submit">Registrar Empleado</button>
        <a href="{{ route('empleados.lista') }}">Cancelar</a>
    </form>
</body>
</html>