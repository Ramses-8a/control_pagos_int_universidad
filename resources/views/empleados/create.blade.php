<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Empleado</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        <form action="{{ route('empleados.store') }}" method="POST" class="form-container" id="createForm">
            @csrf
            
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" name="nombre" id="nombre" class="form-input" value="{{ old('nombre') }}" required>
                @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="apaterno" class="form-label">Apellido paterno:</label>
                <input type="text" name="apaterno" id="apaterno" class="form-input" value="{{ old('apaterno') }}" required>
                @error('apaterno') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="amaterno" class="form-label">Apellido materno:</label>
                <input type="text" name="amaterno" id="amaterno" class="form-input" value="{{ old('amaterno') }}" required>
                @error('amaterno') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" name="correo" id="correo" class="form-input" value="{{ old('correo') }}" required>
                @error('correo') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fk_puestos" class="form-label">Puesto:</label>
                <select name="fk_puestos" id="fk_puestos" class="form-select" required>
                    <option value="">Seleccione un puesto</option>
                    @foreach($puestos as $puesto)
                        <option value="{{ $puesto->id }}" {{ old('fk_puestos') == $puesto->id ? 'selected' : '' }}>
                            {{ $puesto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('fk_puestos') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fk_periodo_pago" class="form-label">Periodo de pago:</label>
                <select name="fk_periodo_pago" id="fk_periodo_pago" class="form-select" required>
                    <option value="">Seleccione un periodo de pago</option>
                    @foreach($periodosPago as $periodo)
                        <option value="{{ $periodo->id }}" {{ old('fk_periodo_pago') == $periodo->id ? 'selected' : '' }}>
                            {{ $periodo->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('fk_periodo_pago') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Registrar Empleado</button>
                <a href="{{ route('empleados.lista') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Registro exitoso!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Errores en el formulario',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Entendido'
            });
        @endif

       
        document.getElementById('createForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar campos 
            const nombre = document.getElementById('nombre').value.trim();
            const apaterno = document.getElementById('apaterno').value.trim();
            const amaterno = document.getElementById('amaterno').value.trim();
            const correo = document.getElementById('correo').value.trim();
            const fk_puestos = document.getElementById('fk_puestos').value;
            const fk_periodo_pago = document.getElementById('fk_periodo_pago').value;

            if (!nombre || !apaterno || !amaterno || !correo || !fk_puestos || !fk_periodo_pago) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos requeridos',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Validar formato de correo
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(correo)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Correo inválido',
                    text: 'Por favor, ingrese un correo electrónico válido',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            Swal.fire({
                title: '¿Registrar empleado?',
                text: '¿Estás seguro de crear este nuevo empleado?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Registrando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    this.submit();
                }
            });
        });
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
                    window.location.href = "{{ route('empleados.lista') }}";
                }
            });
        });
        const formInputs = document.querySelectorAll('.form-input, .form-select');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                const errorSpan = this.parentNode.querySelector('.error-message');
                if (errorSpan) {
                    errorSpan.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
</x-app-layout>