<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Pago</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Registrar nuevo pago</h1>

        <form action="{{ route('pagos.store') }}" method="POST" class="form-container" id="createForm">
            @csrf
            
            <div class="form-group">
                <label for="fk_empleados" class="form-label">Empleado:</label>
                <select name="fk_empleados" id="fk_empleados" class="form-select" required>
                    <option value="">Seleccione un empleado</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}" {{ old('fk_empleados') == $empleado->id ? 'selected' : '' }}>
                            {{ $empleado->nombre }} {{ $empleado->apaterno }} {{ $empleado->amaterno }}
                        </option>
                    @endforeach
                </select>
                @error('fk_empleados') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="monto" class="form-label">Monto:</label>
                <input type="number" name="monto" id="monto" class="form-input" step="0.01" min="0" value="{{ old('monto') }}" placeholder="0.00" required>
                @error('monto') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
            <label for="fecha_pago" class="form-label">Fecha de pago (opcional):</label>
            <input type="date" name="fecha_pago" id="fecha_pago" class="form-input" 
                value="{{ old('fecha_pago', date('Y-m-d')) }}">
            <small class="text-muted">Si no selecciona una fecha, se usará la fecha actual</small>
            @error('fecha_pago') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" class="form-input" value="{{ old('descripcion') }}" placeholder="Ej: Pago quincenal de agosto" required>
                @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fk_proyectos" class="form-label">Proyecto:</label>
                <select name="fk_proyectos" id="fk_proyectos" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" {{ old('fk_proyectos') == $proyecto->id ? 'selected' : '' }}>
                            {{ $proyecto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('fk_proyectos') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Registrar Pago</button>
                <a href="{{ route('pagos.lista') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Manejar envío del formulario
        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Registrar pago?',
                text: '¿Estás seguro de registrar este pago?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Registrando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Enviar formulario
                    this.submit();
                }
            });
        });

        // Manejar clic en Cancelar
        document.querySelector('.btn-secondary').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Cancelar registro?',
                text: 'Los datos ingresados se perderán',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Seguir registrando'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('pagos.lista') }}";
                }
            });
        });
    </script>
</body>
</html>
</x-app-layout>