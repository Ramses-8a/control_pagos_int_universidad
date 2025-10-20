<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Crear Nuevo Puesto</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Crear nuevo puesto</h1>

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

        <form action="{{ route('puestos.guardar') }}" method="POST" class="form-container" id="createForm">
            @csrf
            
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del puesto:</label>
                <input type="text" name="nombre" id="nombre" class="form-input" value="{{ old('nombre') }}" required>
                @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea name="descripcion" id="descripcion" class="form-textarea">{{ old('descripcion') }}</textarea>
                @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Puesto</button>
                <a href="{{ route('puestos.lista') }}" class="btn btn-secondary">Cancelar</a>
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
            const descripcion = document.getElementById('descripcion').value.trim();

            if (!nombre) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'El nombre del puesto es obligatorio',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

           
            
            Swal.fire({
                title: '¿Crear puesto?',
                text: '¿Estás seguro de crear este nuevo puesto?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, crear',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Creando...',
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
            
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            
            if (nombre || descripcion) {
                Swal.fire({
                    title: '¿Cancelar creación?',
                    text: 'Los datos ingresados se perderán',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'Seguir creando'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('puestos.lista') }}";
                    }
                });
            } else {
                window.location.href = "{{ route('puestos.lista') }}";
            }
        });

        const formInputs = document.querySelectorAll('.form-input, .form-textarea');
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