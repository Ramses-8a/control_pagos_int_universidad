<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear servicio</title>
</head>

<body>
    <div class="container">
        <h1>Crear nuevo servicio</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('servicios.guardar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del servicio:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-3">
                <label for="costo" class="form-label">Costo:</label>
                <input type="number" class="form-control" id="costo" name="costo" step="0.01" min="0" value="{{ old('costo') }}" required>
            </div>

            <div class="mb-3">
                <label for="fk_tipo_servicio" class="form-label">Tipo de servicio:</label>
                <select class="form-select" id="fk_tipo_servicio" name="fk_tipo_servicio" required>
                    <option value="" disabled selected>Seleccione un tipo...</option>
                    @foreach ($tipos_servicio as $tipo)
                    <option value="{{ $tipo->id }}" {{ old('fk_tipo_servicio') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('servicios.lista') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

</body>

</html>