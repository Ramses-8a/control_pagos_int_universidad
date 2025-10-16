<!DOCTYPE html>
<html>
<head>
    <title>Editar Puesto</title>
</head>
<body>
    <h1>Editar puesto: {{ $puesto->nombre }}</h1>

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

    <form action="{{ route('puestos.actualizar', $puesto->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div>
            <label for="nombre">Nombre del puesto:</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $puesto->nombre) }}" required>
            @error('nombre') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion">{{ old('descripcion', $puesto->descripcion) }}</textarea>
            @error('descripcion') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="estatus">Estatus:</label>
            <select name="estatus" id="estatus" required>
                <option value="activo" {{ old('estatus', $puesto->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ old('estatus', $puesto->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('estatus') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Actualizar Puesto</button>
        <a href="{{ route('puestos.lista') }}">Cancelar</a>
    </form>
</body>
</html>