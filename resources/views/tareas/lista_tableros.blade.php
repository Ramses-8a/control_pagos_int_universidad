<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/Tableros.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <div class="board-page-container">
        <div class="board-header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Tableros Kanban
            </h2>
        </div>

        <div class="board-grid">
            
            <div class="board-card board-card-create" onclick="openBoardModal()">
                <i class="fas fa-plus"></i>
                <span>Crear Nuevo Tablero</span>
            </div>

            @foreach ($tableros as $tablero)
                <a href="{{ route('tareas.index', ['tablero_id' => $tablero->id]) }}" class="board-card">
                    <div class="board-card-content">
                        <h3>{{ $tablero->nombre }}</h3>
                        <p>{{ $tablero->descripcion }}</p>
                    </div>
                    <div class="board-card-footer">
                        <span class="project-tag">{{ $tablero->proyecto->nombre }}</span>
                        
                        <span class="btn-view-board">
                            Ver Tareas
                        </span>

                        <button onclick="event.preventDefault(); event.stopPropagation(); openBoardModal({{ $tablero->id }});" class="btn-edit-board">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="event.preventDefault(); event.stopPropagation(); deleteBoard({{ $tablero->id }});" class="btn-delete-board">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </a>
            @endforeach

        </div>
    </div>

    <script>
        function openBoardModal(tableroId = null) {
            let projectOptions = "";
            @foreach ($proyectos as $proyecto)
                projectOptions += `<option value='{{ $proyecto->id }}'>{{ $proyecto->nombre }}</option>`;
            @endforeach

            let title = 'Crear Nuevo Tablero';
            let confirmButtonText = 'Guardar Tablero';
            let url = '{{ route('tableros.store') }}';
            let method = 'POST';
            let nombre = '';
            let descripcion = '';
            let proyecto_id = '';

            const showSwal = (nombre = '', descripcion = '', proyecto_id = '') => {
                Swal.fire({
                    title: title,
                    html: `
                        <input type="text" id="swal-nombre" class="swal2-input custom-swal-input" placeholder="Nombre del Tablero" value="${nombre}" required>
                        
                        <textarea id="swal-descripcion" class="swal2-textarea custom-swal-textarea" placeholder="Descripción del Tablero...">${descripcion}</textarea>
                        
                        <select id="swal-proyecto_id" class="swal2-select custom-swal-select" required>
                            <option value="" disabled>-- Asignar a un Proyecto --</option>
                            ${projectOptions}
                        </select>
                    `,
                    confirmButtonText: confirmButtonText,
                    focusConfirm: false,
                    customClass: {
                        
                        
                        popup: 'swal2-popup swal-form-modal', 
                        title: 'swal2-title',
                        confirmButton: 'swal2-confirm',
                        input: 'custom-swal-input',
                        textarea: 'custom-swal-textarea',
                        select: 'custom-swal-select'
                    },
                    didOpen: () => {
                        if (proyecto_id) {
                            document.getElementById('swal-proyecto_id').value = proyecto_id;
                        } else {
                            document.getElementById('swal-proyecto_id').querySelector('option[value=""]').selected = true;
                        }
                    },
                    preConfirm: () => {
                        const nombre = document.getElementById('swal-nombre').value;
                        const proyecto_id = document.getElementById('swal-proyecto_id').value;
                        
                        if (!nombre || !proyecto_id) {
                            Swal.showValidationMessage(`El nombre y el proyecto son obligatorios`);
                            return false;
                        }
                        return {
                            nombre: nombre,
                            descripcion: document.getElementById('swal-descripcion').value,
                            proyecto_id: proyecto_id
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(result.value)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    tableroId ? '¡Actualizado!' : '¡Creado!',
                                    tableroId ? 'Tu tablero ha sido actualizado.' : 'Tu tablero ha sido creado.',
                                    'success'
                                ).then(() => location.reload());
                            } else {
                                Swal.fire('Error', tableroId ? 'No se pudo actualizar el tablero.' : 'No se pudo crear el tablero.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', tableroId ? 'Hubo un problema al actualizar el tablero.' : 'Hubo un problema al crear el tablero.', 'error');
                            console.error('Error:', error);
                        });
                    }
                });
            };

            if (tableroId) {
                title = 'Editar Tablero';
                confirmButtonText = 'Guardar Cambios';
                url = `/tableros/${tableroId}`;
                method = 'PUT';

                fetch(`/tableros/${tableroId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        showSwal(data.nombre, data.descripcion, data.proyecto_id);
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema al cargar los datos del tablero.', 'error');
                        console.error('Error:', error);
                    });
            } else {
                showSwal();
            }
        }

        function deleteBoard(tableroId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
                
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/tableros/${tableroId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ estado: 0, _method: 'PUT' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Actualizado!',
                                'El estado del tablero ha sido actualizado.',
                                'success'
                            ).then(() => location.reload());
                        } else {
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar el tablero.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error',
                            'Hubo un problema al eliminar el tablero.',
                            'error'
                        );
                        console.error('Error:', error);
                    });
                }
            });
        }
    </script>
</x-app-layout>