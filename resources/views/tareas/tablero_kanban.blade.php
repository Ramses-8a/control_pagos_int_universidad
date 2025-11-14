<x-app-layout>
    {{-- Librerías CSS y JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- Hoja de estilos --}}
    <link rel="stylesheet" href="{{ asset('css/Tareas.css') }}">
    
    {{-- Meta de CSRF para las peticiones AJAX (¡Muy importante!) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="kanban-board-container">

        {{-- Encabezado del Tablero --}}
        <div class="kanban-header">
            <h1 class="kanban-title">Tablero de Tareas del Proyecto</h1>
            <button class="kanban-create-task-btn" id="createTaskBtn">+ Crear Tarea</button>
        </div>

        {{-- Contenedor de las Columnas --}}
        <div class="kanban-board">

            @foreach ($estatuses as $estatus)
                <div class="kanban-column" data-status-id="{{ $estatus->id }}">
                    <div class="kanban-column-header" style="border-top-color: {{ $estatus->color }};">
                        <span class="kanban-column-title">{{ $estatus->nombre }}</span>
                        <span class="kanban-column-count">
                            {{ $tareas->where('fk_estatus_tarea', $estatus->id)->count() }}
                        </span>
                    </div>
                    <div class="kanban-task-list" data-status-id="{{ $estatus->id }}">
                        {{-- Itera sobre las tareas de esta columna --}}
                        @foreach ($tareas->where('fk_estatus_tarea', $estatus->id) as $tarea)
                            <div class="kanban-task" data-task-id="{{ $tarea->id }}">
                                
                                {{-- Encabezado de la tarjeta con título y botón de borrar --}}
                                <div class="kanban-task-header">
                                    <span class="kanban-task-title">{{ $tarea->titulo }}</span>
                                    <button class="kanban-task-delete-btn" data-task-id="{{ $tarea->id }}">&times;</button>
                                </div>
                                
                                <p class="kanban-task-description">{{ $tarea->notas }}</p>
                                
                                <div class="kanban-task-date">
                                    <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($tarea->fecha_fin)->format('d M') }}
                                </div>
                                
                                <div class="kanban-task-footer">
                                    <div class="kanban-task-assignee" title="{{ $tarea->empleado->nombre_completo }}">
                                        @if($tarea->empleado)
                                            {{ $tarea->empleado->iniciales }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <span class="kanban-task-id">#{{ $tarea->id }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- 
    ================================================================
    SCRIPT DE INICIALIZACIÓN
    Contiene toda la lógica para Drag&Drop, Crear, Editar y Eliminar.
    ================================================================
    --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. DATOS DINÁMICOS (Desde el controlador) ---
            const empleados = @json($empleados);
            const estatuses = @json($estatuses);
            const CURRENT_PROYECTO_ID = @json($proyecto_id ?? null); 
            
            // --- CORRECCIÓN AQUÍ ---
            // Añadimos el ID del tablero actual al JS
            const CURRENT_TABLERO_ID = @json($tablero_id ?? null); 
            
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
            // --- 2. FUNCIONALIDAD DRAG & DROP ---
            const taskLists = document.querySelectorAll('.kanban-task-list');
            
            taskLists.forEach(list => {
                new Sortable(list, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'kanban-task-ghost',
                    
                    onEnd: function (evt) {
                        const taskEl = evt.item;
                        const toList = evt.to; 
                        
                        const taskId = taskEl.dataset.taskId;
                        const newStatusId = toList.dataset.statusId;
    
                        fetch(`/tareas/${taskId}/update-status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify({ fk_estatus_tarea: newStatusId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                console.log('Estatus actualizado en la BD');
                                updateColumnCounts();
                            } else {
                                console.error('Error al actualizar la tarea');
                                Swal.fire('Error', 'No se pudo actualizar el estado de la tarea.', 'error');
                            }
                        })
                        .catch(error => {
                             console.error('Error de red:', error);
                             Swal.fire('Error', 'Error de conexión al actualizar el estado de la tarea.', 'error');
                        });
                    }
                });
            });
    
            // --- 3. ACTUALIZAR CONTADORES ---
            function updateColumnCounts() {
                document.querySelectorAll('.kanban-column').forEach(column => {
                    const count = column.querySelectorAll('.kanban-task').length;
                    column.querySelector('.kanban-column-count').textContent = count;
                });
            }
    
            // --- 4. MODAL CREAR TAREA (SweetAlert2) ---
            document.getElementById('createTaskBtn').addEventListener('click', () => {
                
                const empleadosOptions = empleados.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');
                const estatusOptions = estatuses.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');
    
                Swal.fire({
                    title: 'Crear Nueva Tarea',
                    html: getTaskFormHtml(null, empleadosOptions, estatusOptions),
                    showCancelButton: true,
                    confirmButtonText: 'Crear Tarea',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    preConfirm: () => {
                        return validateAndGetFormData(true); // true = creando
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const taskData = result.value;
                        
                        fetch('/tareas', { 
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify(taskData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                addNewTaskToBoard(data.tarea); 
                                Swal.fire('¡Creada!', 'La tarea ha sido creada.', 'success');
                            } else {
                                // Muestra un error más detallado si la validación falla
                                let errorMsg = data.message || 'No se pudo crear la tarea.';
                                if(data.errors) {
                                    errorMsg += '<br>' + Object.values(data.errors).join('<br>');
                                }
                                Swal.fire('Error', errorMsg, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error de red:', error);
                            Swal.fire('Error', 'Error de conexión.', 'error');
                        });
                    }
                });
            });
    
            // --- 5. FUNCIÓN PARA AÑADIR TAREA AL DOM ---
            function addNewTaskToBoard(tarea) {
                const columnList = document.querySelector(`.kanban-task-list[data-status-id="${tarea.fk_estatus_tarea}"]`);
                if (!columnList) return;
    
                const taskElement = document.createElement('div');
                taskElement.className = 'kanban-task';
                taskElement.dataset.taskId = tarea.id;
                taskElement.innerHTML = getTaskCardHtml(tarea); 
                
                columnList.appendChild(taskElement);
                updateColumnCounts();
            }
    
            // --- 6. DELEGACIÓN DE CLIC (Para Editar y Eliminar) ---
            document.querySelector('.kanban-board').addEventListener('click', (event) => {
                
                const deleteButton = event.target.closest('.kanban-task-delete-btn');
                const taskElement = event.target.closest('.kanban-task');
    
                if (deleteButton) {
                    event.stopPropagation();
                    const taskId = deleteButton.dataset.taskId;
                    openDeleteConfirm(taskId);
                
                } else if (taskElement) {
                    const taskId = taskElement.dataset.taskId;
                    openEditModal(taskId);
                }
            });
    
            // --- 7. ABRIR MODAL DE EDICIÓN ---
            function openEditModal(taskId) {
                Swal.fire({
                    title: 'Cargando tarea...',
                    didOpen: () => Swal.showLoading()
                });
    
                fetch(`/tareas/${taskId}/edit`)
                    .then(response => {
                        if (!response.ok) throw new Error('No se pudo cargar la tarea');
                        return response.json();
                    })
                    .then(taskData => {
                        const empleadosOptions = empleados.map(e => 
                            `<option value="${e.id}" ${e.id == taskData.fk_empleados ? 'selected' : ''}>${e.nombre}</option>`
                        ).join('');
                        const estatusOptions = estatuses.map(e => 
                            `<option value="${e.id}" ${e.id == taskData.fk_estatus_tarea ? 'selected' : ''}>${e.nombre}</option>`
                        ).join('');
                        
                        Swal.fire({
                            title: 'Editar Tarea',
                            html: getTaskFormHtml(taskData, empleadosOptions, estatusOptions),
                            showCancelButton: true,
                            confirmButtonText: 'Guardar Cambios',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            preConfirm: () => {
                                return validateAndGetFormData(false); // false = no es creación
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const updatedTaskData = result.value;
                                
                                fetch(`/tareas/${taskId}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': CSRF_TOKEN
                                    },
                                    body: JSON.stringify(updatedTaskData)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(data.success) {
                                        updateTaskInDOM(data.tarea);
                                        Swal.fire('¡Actualizado!', 'La tarea ha sido actualizada.', 'success');
                                    } else {
                                        Swal.fire('Error', 'No se pudo actualizar la tarea.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error de red:', error);
                                    Swal.fire('Error', 'Error de conexión.', 'error');
                                });
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', error.message || 'No se pudo cargar la tarea.', 'error');
                    });
            }
    
            // --- 8. FUNCIÓN PARA ACTUALIZAR TAREA EN EL DOM ---
            function updateTaskInDOM(tarea) {
                const taskElement = document.querySelector(`.kanban-task[data-task-id="${tarea.id}"]`);
                if (!taskElement) return;
    
                taskElement.innerHTML = getTaskCardHtml(tarea);
                
                const currentColumnList = taskElement.closest('.kanban-task-list');
                const newColumnList = document.querySelector(`.kanban-task-list[data-status-id="${tarea.fk_estatus_tarea}"]`);
    
                if (currentColumnList && newColumnList && currentColumnList.dataset.statusId != newColumnList.dataset.statusId) {
                    newColumnList.appendChild(taskElement);
                }
                
                updateColumnCounts();
            }
    
            // --- 9. FUNCIONES REUTILIZABLES (Helpers) ---
    
            /**
             * Devuelve el HTML para el formulario de Tarea (Crear/Editar)
             */
            function getTaskFormHtml(taskData, empleadosOptions, estatusOptions) {
                const fechaInicio = (taskData?.fecha_inicio) ? taskData.fecha_inicio.split('T')[0] : new Date().toISOString().split('T')[0];
                const fechaFin = (taskData?.fecha_fin) ? taskData.fecha_fin.split('T')[0] : '';
    
                return `
                    <form id="taskForm" class="swal-form">
                        <div class="swal-form-group">
                            <label for="swal-titulo">Título de la Tarea</label>
                            <input id="swal-titulo" class="swal2-input" placeholder="Ej: Desarrollar API..." 
                                   value="${taskData?.titulo || ''}" required>
                        </div>
                        <div class="swal-form-group">
                            <label for="swal-notas">Notas</label>
                            <textarea id="swal-notas" class="swal2-textarea" placeholder="Notas adicionales...">${taskData?.notas || ''}</textarea>
                        </div>
                        
                        <div class="swal-form-row">
                            <div class="swal-form-group">
                                <label for="swal-fecha-inicio">Fecha de Inicio</label>
                                <input type="date" id="swal-fecha-inicio" class="swal2-input" 
                                       value="${fechaInicio}">
                            </div>
                            <div class="swal-form-group">
                                <label for="swal-fecha-fin">Fecha Límite</label>
                                <input type="date" id="swal-fecha-fin" class="swal2-input" 
                                       value="${fechaFin}">
                            </div>
                        </div>
    
                        <div class="swal-form-row">
                            <div class="swal-form-group">
                                <label for="swal-empleado">Asignar a</label>
                                <select id="swal-empleado" class="swal2-input">
                                    <option value="">Seleccionar empleado...</option>
                                    ${empleadosOptions}
                                </select>
                            </div>
                            <div class="swal-form-group">
                                <label for="swal-estatus">Estatus</label>
                                <select id="swal-estatus" class="swal2-input">
                                    ${estatusOptions}
                                </select>
                            </div>
                        </div>
                    </form>
                `;
            }
    
            /**
             * Valida y recolecta los datos del formulario de SweetAlert
             */
            function validateAndGetFormData(isCreating = true) {
                const titulo = document.getElementById('swal-titulo').value;
                const notas = document.getElementById('swal-notas').value;
                const fechaInicio = document.getElementById('swal-fecha-inicio').value;
                const fechaFin = document.getElementById('swal-fecha-fin').value;
                const empleadoId = document.getElementById('swal-empleado').value;
                const estatusId = document.getElementById('swal-estatus').value;
    
                // Validaciones
                if (!titulo) { Swal.showValidationMessage(`El título es obligatorio`); return false; }
                if (!fechaInicio) { Swal.showValidationMessage(`La fecha de inicio es obligatoria`); return false; }
                if (!fechaFin) { Swal.showValidationMessage(`La fecha límite es obligatoria`); return false; }
                if (!empleadoId) { Swal.showValidationMessage(`Debes asignar un empleado`); return false; }
                
                // --- CORRECCIÓN AQUÍ ---
                // Validar Proyecto Y Tablero al crear
                if (isCreating) {
                    if (!CURRENT_PROYECTO_ID) {
                        Swal.showValidationMessage(`Error: No se encontró ID del proyecto.`); 
                        return false;
                    }
                    if (!CURRENT_TABLERO_ID) {
                        Swal.showValidationMessage(`Error: No se encontró ID del tablero.`); 
                        return false;
                    }
                }
                // --- FIN DE LA CORRECCIÓN ---
                
                // Objeto de datos
                let taskData = {
                    titulo: titulo,
                    notas: notas,
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin,
                    fk_empleados: empleadoId,
                    fk_estatus_tarea: estatusId,
                };
    
                // Añade IDs de proyecto y tablero solo al crear
                if (isCreating) {
                    taskData.fk_proyectos_id = CURRENT_PROYECTO_ID;
                    // --- CORRECCIÓN AQUÍ ---
                    taskData.fk_tablero_proyecto = CURRENT_TABLERO_ID;
                }
    
                return taskData;
            }
    
            /**
             * Devuelve el HTML interno de una tarjeta de Tarea
             */
            function getTaskCardHtml(tarea) {
                const empleado = tarea.empleado || { nombre_completo: 'N/A', iniciales: '??', color: '#ccc' };
                const notas = tarea.notas || '';
    
                return `
                    <div class="kanban-task-header">
                        <span class="kanban-task-title">${tarea.titulo}</span>
                        <button class="kanban-task-delete-btn" data-task-id="${tarea.id}">&times;</button>
                    </div>
                    <p class="kanban-task-description">${notas}</p>
                    <div class="kanban-task-date">
                        <i class="far fa-calendar-alt"></i> ${formatTaskDate(tarea.fecha_fin)}
                    </div>
                    <div class="kanban-task-footer">
                        <div class="kanban-task-assignee" style="background-color: ${empleado.color || '#673ab7'}" title="${empleado.nombre_completo}">
                            ${empleado.iniciales}
                        </div>
                        <span class="kanban-task-id">#${tarea.id}</span>
                    </div>
                `;
            }
    
            /**
             * Formatea fecha YYYY-MM-DD a "dd Mmm" (ej: 14 Nov)
             */
            function formatTaskDate(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString.split('T')[0] + 'T00:00:00');
                let formatted = date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                return formatted.replace('.', '');
            }

            // --- 10. FUNCIÓN DE CONFIRMACIÓN DE BORRADO ---
            function openDeleteConfirm(taskId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede revertir.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteTask(taskId);
                    }
                });
            }
    
            // --- 11. FUNCIÓN PARA ELIMINAR TAREA (Fetch) ---
            function deleteTask(taskId) {
                fetch(`/tareas/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const taskElement = document.querySelector(`.kanban-task[data-task-id="${taskId}"]`);
                        if (taskElement) {
                            taskElement.remove();
                            updateColumnCounts();
                        }
                        Swal.fire('¡Eliminado!', 'La tarea ha sido eliminada.', 'success');
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar la tarea.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error de red:', error);
                    Swal.fire('Error', 'Error de conexión.', 'error');
                });
            }
    
        });
    </script>
</x-app-layout>