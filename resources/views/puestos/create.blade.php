<!DOCTYPE html>
<html>
<head>
    <title>Crear Nuevo Puesto</title>
</head>
<body>
    <h1>Crear Nuevo Puesto</h1>

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

    <form action="{{ route('puestos.guardar') }}" method="POST">
        @csrf
        
        <div>
            <label for="nombre">Nombre del puesto:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
            @error('nombre') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion">{{ old('descripcion') }}</textarea>
            @error('descripcion') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Guardar Puesto</button>
        <a href="{{ route('puestos.lista') }}">Cancelar</a>
    </form>
</body>
</html>