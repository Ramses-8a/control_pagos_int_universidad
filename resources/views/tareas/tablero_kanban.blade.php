<x-app-layout>
    {{-- Librerías CSS y JS --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/Tareas.css') }}">
    
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
                    @foreach ($tareas->where('fk_estatus_tarea', $estatus->id) as $tarea)
                        <div class="kanban-task" data-task-id="{{ $tarea->id }}">
                            <div class="kanban-task-header">
                                <span class="kanban-task-title">{{ $tarea->titulo }}</span>
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
    3. SCRIPT DE INICIALIZACIÓN
    Este script debe ejecutarse DESPUÉS de que el HTML esté cargado.
    Si usas Blade, un @push('scripts') sería ideal.
--}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. DATOS DINÁMICOS (Desde el controlador) ---
        const empleados = @json($empleados);
        const estatuses = @json($estatuses);
        // (Asegúrate de que tu controlador pasa $proyecto_id a esta vista)
        const CURRENT_PROYECTO_ID = @json($proyecto_id ?? null); 
        const CURRENT_TABLERO_ID = @json($tablero_id ?? null);

        // --- 2. FUNCIONALIDAD DRAG & DROP ---
        const taskLists = document.querySelectorAll('.kanban-task-list');
        
        taskLists.forEach(list => {
            new Sortable(list, {
                group: 'kanban', // Permite arrastrar entre listas
                animation: 150,
                ghostClass: 'kanban-task-ghost', // Clase CSS para el placeholder
                
                // Evento cuando se suelta una tarea en una nueva columna
                onEnd: function (evt) {
                    const taskEl = evt.item; // El elemento de la tarea movida
                    const toList = evt.to; // La lista de destino
                    
                    const taskId = taskEl.dataset.taskId;
                    const newStatusId = toList.dataset.statusId;

                    // Aquí es donde harías la llamada a la BD para actualizar el estado
                    console.log(`Tarea ID: ${taskId} movida a Estatus ID: ${newStatusId}`);
                    
                    // --- INICIO: LÓGICA DE ACTUALIZACIÓN (AJAX/Fetch) ---
                    fetch(`/tareas/${taskId}/update-status`, {
                        method: 'PATCH', // O PUT/PATCH
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Importante para Laravel
                        },
                        body: JSON.stringify({
                            fk_estatus_tarea: newStatusId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            console.log('Estatus actualizado en la BD');
                            updateColumnCounts();
                            // Swal.fire('¡Actualizado!', 'El estado de la tarea ha sido actualizado.', 'success');
                        } else {
                            // Revertir el movimiento si falla
                            // (SortableJS no lo hace automáticamente)
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
                const statusId = column.dataset.statusId;
                const count = column.querySelectorAll('.kanban-task').length;
                column.querySelector('.kanban-column-count').textContent = count;
            });
        }

        // --- 4. MODAL CREAR TAREA (SweetAlert2) ---
        document.getElementById('createTaskBtn').addEventListener('click', () => {
            
            // Generar opciones HTML para los <select>
            const empleadosOptions = empleados.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');
            const estatusOptions = estatuses.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');

            Swal.fire({
                title: 'Crear Nueva Tarea',
                html: `
                    <form id="createTaskForm" class="swal-form">
                        <div class="swal-form-group">
                            <label for="swal-titulo">Título de la Tarea</label>
                            <input id="swal-titulo" class="swal2-input" placeholder="Ej: Desarrollar API..." required>
                        </div>
                        <div class="swal-form-group">
                            <label for="swal-notas">Notas</label>
                            <textarea id="swal-notas" class="swal2-textarea" placeholder="Notas adicionales..."></textarea>
                        </div>
                        
                        <!-- Contenedor de Fila para Fechas -->
                        <div class="swal-form-row">
                            <div class="swal-form-group">
                                <label for="swal-fecha-inicio">Fecha de Inicio</label>
                                <input type="date" id="swal-fecha-inicio" class="swal2-input">
                            </div>
                            <div class="swal-form-group">
                                <label for="swal-fecha-fin">Fecha Límite</label>
                                <input type="date" id="swal-fecha-fin" class="swal2-input">
                            </div>
                        </div>

                        <!-- Contenedor de Fila para Asignación y Estatus -->
                        <div class="swal-form-row">
                            <div class="swal-form-group">
                                <label for="swal-empleado">Asignar a</label>
                                <select id="swal-empleado" class="swal2-input">
                                    <option value="">Seleccionar empleado...</option>
                                    ${empleadosOptions}
                                </select>
                            </div>
                            <div class="swal-form-group">
                                <label for="swal-estatus">Estatus Inicial</label>
                                <select id="swal-estatus" class="swal2-input">
                                    ${estatusOptions}
                                </select>
                            </div>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Crear Tarea',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6', // Color formal
                cancelButtonColor: '#d33',
                preConfirm: () => {
                    // Validación y recolección de datos del formulario
                    const titulo = document.getElementById('swal-titulo').value;
                    const notas = document.getElementById('swal-notas').value;
                    const fechaInicio = document.getElementById('swal-fecha-inicio').value;
                    const fechaFin = document.getElementById('swal-fecha-fin').value;
                    const empleadoId = document.getElementById('swal-empleado').value;
                    const estatusId = document.getElementById('swal-estatus').value;

                    if (!titulo) {
                        Swal.showValidationMessage(`El título es obligatorio`);
                        return false;
                    }
                    if (!fechaInicio) {
                        Swal.showValidationMessage(`La fecha de inicio es obligatoria`);
                        return false;
                    }
                    if (!fechaFin) {
                        Swal.showValidationMessage(`La fecha límite es obligatoria`);
                        return false;
                    }
                    if (!empleadoId) {
                        Swal.showValidationMessage(`Debes asignar un empleado`);
                        return false;
                    }
                    if (!CURRENT_PROYECTO_ID) {
                        Swal.showValidationMessage(`Error: No se encontró ID del proyecto.`);
                        return false;
                    }
                    
                    return {
                        titulo: titulo,
                        notas: notas,
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin,
                        fk_empleados: empleadoId,
                        fk_estatus_tarea: estatusId,
                        fk_proyectos_id: CURRENT_PROYECTO_ID,
                    fk_tablero_proyecto: CURRENT_TABLERO_ID, 
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const taskData = result.value;
                    console.log('Datos de la nueva tarea:', taskData);

                    // --- INICIO: LÓGICA DE CREACIÓN (AJAX/Fetch) ---
                    fetch('/tareas', { // Ruta de tu store() en Laravel
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(taskData) // taskData ya incluye el fk_proyectos_id
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            // Si la BD lo crea, lo añadimos al DOM
                            addNewTaskToBoard(data.tarea); // data.tarea debe ser el objeto de la tarea creada
                            Swal.fire('¡Creada!', 'La tarea ha sido creada.', 'success');
                        } else {
                            Swal.fire('Error', 'No se pudo crear la tarea.', 'error');
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

            taskElement.innerHTML = `
                <div class="kanban-task-header">
                    <span class="kanban-task-title">${tarea.titulo}</span>
                </div>
                <p class="kanban-task-description">${tarea.notas}</p>
                <div class="kanban-task-date">
                    <i class="far fa-calendar-alt"></i> ${new Date(tarea.fecha_inicio).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' }).replace('.', '')} - ${new Date(tarea.fecha_fin).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' }).replace('.', '')}
                </div>
                <div class="kanban-task-footer">
                    <div class="kanban-task-assignee" style="background-color: ${tarea.empleado.color || '#673ab7'}" title="${tarea.empleado.nombre_completo}">
                        ${tarea.empleado.iniciales}
                    </div>
                    <span class="kanban-task-id">#${tarea.id}</span>
                </div>
            `;
            
            taskElement.addEventListener('click', () => showTaskDetailsModal(tarea));

            columnList.appendChild(taskElement);
            updateColumnCounts();
        }

        // --- 6. FUNCIÓN PARA MOSTRAR DETALLES DE LA TAREA EN UN MODAL ---
        function showTaskDetailsModal(tarea) {
            const empleadoInfo = tarea.empleado ? 
                `<div class="detail-item"><label>Asignado a:</label><span>${tarea.empleado.nombre_completo} (${tarea.empleado.iniciales})</span></div>` : 
                `<div class="detail-item"><label>Asignado a:</label><span>No asignado</span></div>`;
            
            const estatusInfo = tarea.estatus_tarea ? 
                `<div class="detail-item"><label>Estatus:</label><span style="color: ${tarea.estatus_tarea.color || '#000'};">${tarea.estatus_tarea.nombre}</span></div>` : 
                `<div class="detail-item"><label>Estatus:</label><span>Desconocido</span></div>`;

            Swal.fire({
                title: `Detalles de la Tarea #${tarea.id}`,
                html: `
                    <div class="task-detail-modal">
                        <div class="detail-item"><label>Título:</label><span>${tarea.titulo}</span></div>
                        <div class="detail-item"><label>Notas:</label><span>${tarea.notas || 'Sin notas'}</span></div>
                        <div class="detail-item"><label>Fecha de Inicio:</label><span>${new Date(tarea.fecha_inicio).toLocaleDateString('es-ES')}</span></div>
                        <div class="detail-item"><label>Fecha Límite:</label><span>${new Date(tarea.fecha_fin).toLocaleDateString('es-ES')}</span></div>
                        ${empleadoInfo}
                        ${estatusInfo}
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    container: 'swal2-container-custom',
                    popup: 'swal2-popup-custom'
                }
            });
        }

        // Añadir event listeners a las tareas existentes al cargar la página
        document.querySelectorAll('.kanban-task').forEach(taskElement => {
            const taskId = taskElement.dataset.taskId;
            // Necesitamos obtener el objeto tarea completo para pasarlo al modal
            // Esto requerirá una llamada AJAX o tener todas las tareas disponibles en JS
            // Por ahora, asumiremos que podemos reconstruir la tarea o que ya está disponible.
            // Para simplificar, si la tarea ya está en el DOM, podemos extraer la info.
            // Sin embargo, la forma más robusta es tener un array de tareas en JS.
            // Por ahora, haremos una simulación o asumiremos que la tarea se puede obtener.
            
            // Para las tareas existentes, necesitamos una forma de obtener el objeto completo.
            // La forma más sencilla es que el backend pase todas las tareas con sus relaciones cargadas.
            // Si no, tendríamos que hacer una llamada AJAX por cada tarea al hacer clic.
            // Asumiremos que $tareas ya tiene las relaciones cargadas y podemos mapearlas a JS.
            
            // Para evitar duplicar la lógica, vamos a refactorizar un poco.
            // Primero, vamos a crear un mapa de tareas por ID.
            const allTasks = @json($tareas->load('empleado', 'estatusTarea'));
            const tasksMap = new Map(allTasks.map(task => [task.id.toString(), task]));

            taskElement.addEventListener('click', () => {
                const task = tasksMap.get(taskId);
                if (task) {
                    showTaskDetailsModal(task);
                } else {
                    console.error('Tarea no encontrada en el mapa:', taskId);
                }
            });
        });

    });
</script>

<style>
    /* Estilos para el modal de detalles de tarea */
    .task-detail-modal {
        text-align: left;
        padding: 10px;
    }
    .task-detail-modal .detail-item {
        margin-bottom: 10px;
    }
    .task-detail-modal .detail-item label {
        font-weight: bold;
        margin-right: 5px;
        color: #333;
        display: inline-block;
        min-width: 100px; /* Alineación */
    }
    .task-detail-modal .detail-item span {
        color: #555;
    }
    .swal2-popup-custom {
        width: 600px !important; /* Ajusta el ancho del modal */
    }
</style>

</x-app-layout>