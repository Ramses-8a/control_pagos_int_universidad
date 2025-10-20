<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Puesto</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        <form action="{{ route('puestos.actualizar', $puesto->id) }}" method="POST" class="form-container" id="editForm">
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
                    <option value="1" {{ old('estatus', $puesto->estatus) == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estatus', $puesto->estatus) == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estatus') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Puesto</button>
                <a href="{{ route('puestos.lista') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
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
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
        
            const nombre = document.getElementById('nombre').value.trim();
            const estatus = document.getElementById('estatus').value;

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
                title: '¿Actualizar puesto?',
                text: '¿Estás seguro de guardar los cambios?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Actualizando...',
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
            const originalData = {
                nombre: "{{ $puesto->nombre }}",
                descripcion: "{{ $puesto->descripcion }}",
                estatus: "{{ $puesto->estatus }}"
            };

            const currentData = {
                nombre: document.getElementById('nombre').value.trim(),
                descripcion: document.getElementById('descripcion').value.trim(),
                estatus: document.getElementById('estatus').value
            };

            const hasChanges = 
                currentData.nombre !== originalData.nombre ||
                currentData.descripcion !== originalData.descripcion ||
                currentData.estatus !== originalData.estatus;

            if (hasChanges) {
                Swal.fire({
                    title: '¿Cancelar cambios?',
                    text: 'Los cambios no guardados se perderán',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'Seguir editando'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('puestos.lista') }}";
                    }
                });
            } else {
            
                window.location.href = "{{ route('puestos.lista') }}";
            }
        });

        const formInputs = document.querySelectorAll('.form-input, .form-textarea, .form-select');
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