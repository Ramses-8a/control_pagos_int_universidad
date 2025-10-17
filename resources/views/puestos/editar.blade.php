<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Puesto</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="page-title">Editar puesto: {{ $puesto->nombre }}</h1>

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

        <form action="{{ route('puestos.actualizar', $puesto->id) }}" method="POST" class="form-container">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del puesto:</label>
                <input type="text" name="nombre" id="nombre" class="form-input" value="{{ old('nombre', $puesto->nombre) }}" required>
                @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-textarea">{{ old('descripcion', $puesto->descripcion) }}</textarea>
                @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="estatus" class="form-label">Estatus:</label>
                <select name="estatus" id="estatus" class="form-select" required>
                    <option value="activo" {{ old('estatus', $puesto->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estatus', $puesto->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estatus') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Puesto</button>
                <a href="{{ route('puestos.lista') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
</x-app-layout>