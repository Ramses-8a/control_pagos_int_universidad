<x-app-layout>
<!DOCTYPE html>
<html>
<head>
    <title>Editar Pago</title>
    <link href="{{ asset('css/formulario.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Editar Pago</h1>

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

        <form action="{{ route('pagos.actualizar', $pago->id) }}" method="POST" class="form-container" id="editForm">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="fk_empleados" class="form-label">Empleado:</label>
                <select name="fk_empleados" id="fk_empleados" class="form-select" required>
                    <option value="">Seleccione un empleado</option>
                    @foreach($empleados as $empleado)
                        <option value="{{ $empleado->id }}" {{ old('fk_empleados', $pago->fk_empleados) == $empleado->id ? 'selected' : '' }}>
                            {{ $empleado->nombre }} {{ $empleado->apaterno }} {{ $empleado->amaterno }}
                        </option>
                    @endforeach
                </select>
                @error('fk_empleados') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="monto" class="form-label">Monto:</label>
                <input type="number" name="monto" id="monto" class="form-input" step="0.01" min="0" 
                       value="{{ old('monto', $pago->monto) }}" placeholder="0.00" required>
                @error('monto') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fecha_pago" class="form-label">Fecha de pago:</label>
                <input type="date" name="fecha_pago" id="fecha_pago" class="form-input" 
                       value="{{ old('fecha_pago', $pago->fecha_pago) }}" required>
                @error('fecha_pago') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" class="form-input" 
                       value="{{ old('descripcion', $pago->descripcion) }}" placeholder="Ej: Pago quincenal de agosto" required>
                @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="fk_proyectos" class="form-label">Proyecto:</label>
                <select name="fk_proyectos" id="fk_proyectos" class="form-select" required>
                    <option value="">Seleccione un proyecto</option>
                    @foreach($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" {{ old('fk_proyectos', $pago->fk_proyectos) == $proyecto->id ? 'selected' : '' }}>
                            {{ $proyecto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('fk_proyectos') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Pago</button>
                <a href="{{ route('pagos.lista') }}" class="btn btn-secondary">Cancelar</a>
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
        
            const empleado = document.getElementById('fk_empleados').value;
            const monto = document.getElementById('monto').value.trim();
            const fechaPago = document.getElementById('fecha_pago').value;
            const descripcion = document.getElementById('descripcion').value.trim();
            const proyecto = document.getElementById('fk_proyectos').value;

            // Validaciones básicas
            if (!empleado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Debe seleccionar un empleado',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            if (!monto || parseFloat(monto) <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Monto inválido',
                    text: 'El monto debe ser mayor a 0',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            if (!fechaPago) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'La fecha de pago es obligatoria',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            if (!descripcion) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'La descripción es obligatoria',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            if (!proyecto) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Debe seleccionar un proyecto',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            Swal.fire({
                title: '¿Actualizar pago?',
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
            
            // Datos originales del pago
            const originalData = {
                empleado: "{{ $pago->fk_empleados }}",
                monto: "{{ $pago->monto }}",
                fecha_pago: "{{ $pago->fecha_pago }}",
                descripcion: "{{ $pago->descripcion }}",
                proyecto: "{{ $pago->fk_proyectos }}"
            };

            // Datos actuales del formulario
            const currentData = {
                empleado: document.getElementById('fk_empleados').value,
                monto: document.getElementById('monto').value.trim(),
                fecha_pago: document.getElementById('fecha_pago').value,
                descripcion: document.getElementById('descripcion').value.trim(),
                proyecto: document.getElementById('fk_proyectos').value
            };

            // Verificar si hay cambios
            const hasChanges = 
                currentData.empleado !== originalData.empleado ||
                currentData.monto !== originalData.monto ||
                currentData.fecha_pago !== originalData.fecha_pago ||
                currentData.descripcion !== originalData.descripcion ||
                currentData.proyecto !== originalData.proyecto;

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
                        window.location.href = "{{ route('pagos.lista') }}";
                    }
                });
            } else {
                window.location.href = "{{ route('pagos.lista') }}";
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

        // Formatear monto al perder el foco
        document.getElementById('monto').addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    </script>
</body>
</html>
</x-app-layout>