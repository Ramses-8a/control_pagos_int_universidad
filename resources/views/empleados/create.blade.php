<!DOCTYPE html>
<html>
<head>
    <title>Registrar Empleado</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">

</head>
<body>
    <div class="container">
        <h1 class="page-title">Registrar nuevo empleado</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('empleados.store') }}" method="POST" class="form-container">
            @csrf
            
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-input" value="{{ old('nombre') }}" required>
            </div>

            <div class="form-group">
                <label for="apellidos" class="form-label">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" class="form-input" value="{{ old('apellidos') }}" required>
            </div>

            <div class="form-group">
                <label for="puesto_id" class="form-label">Puesto:</label>
                <select name="puesto_id" id="puesto_id" class="form-select" required>
                    <option value="">Seleccione un puesto</option>
                    @foreach($puestos as $puesto)
                        <option value="{{ $puesto->id }}" {{ old('puesto_id') == $puesto->id ? 'selected' : '' }}>
                            {{ $puesto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('puesto_id') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" class="form-input" value="{{ old('telefono') }}">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Registrar Empleado</button>
                <a href="{{ route('empleados.lista') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>