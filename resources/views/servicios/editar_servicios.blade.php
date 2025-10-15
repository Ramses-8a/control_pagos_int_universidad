<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar servicio</title>
</head>

<body>
    <div class="container">
        <h1>Editar servicio</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form action="{{ route('servicios.actualizar', $servicio->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del servicio:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
            </div>
            <div class="mb-3">
                <label for="costo" class="form-label">Costo:</label>
                <input type="number" class="form-control" id="costo" name="costo" step="0.01" min="0" value="{{ old('costo', $servicio->costo) }}" required>
            </div>
            <div class="mb-3">
                <label for="fk_tipo_servicio" class="form-label">Tipo de servicio:</label>
                <select class="form-select" id="fk_tipo_servicio" name="fk_tipo_servicio" required>
                    @foreach ($tipos_servicio as $tipo)
                    <option value="{{ $tipo->id }}" {{ old('fk_tipo_servicio', $servicio->fk_tipo_servicio) == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select class="form-select" id="estatus" name="estatus" required>
                    <option value="activo" {{ old('estatus', $servicio->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estatus', $servicio->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('servicios.lista') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>

</html>