<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tipo de Servicio</title>
</head>

<body>
    <div class="container">
        <h1>Editar tipo servicio</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif
        <form action="{{ route('tipo_servicios.actualizar', $tipoServicio->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del tipo de servicio:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $tipoServicio->nombre) }}" required>
            </div>
            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus:</label>
                <select class="form-select" id="estatus" name="estatus" required>
                    <option value="activo" {{ old('estatus', $tipoServicio->estatus) == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estatus', $tipoServicio->estatus) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a href="{{ route('tipo_servicios.lista') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>

</html>