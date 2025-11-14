<?php

namespace App\Http\Controllers;

use App\Models\TableroProyecto;
use App\Models\EstatusTarea;
use App\Models\Empleado;
use App\Models\Tarea;
use Illuminate\Http\Request;

class TareasController extends Controller
{
    /**
     * Muestra el tablero kanban y sus tareas.
     */
    public function index($tablero_id = null)
    {
        $estatuses = EstatusTarea::all();
        $empleados = Empleado::all();

        if ($tablero_id) {
            $tablero = TableroProyecto::with(['tareas.empleado', 'tareas.estatusTarea'])->findOrFail($tablero_id);
            $tareas = $tablero->tareas;
            $proyecto_id = $tablero->fk_proyecto; // Obtener el ID del proyecto del tablero
        } else {
            $tareas = collect(); // O manejar todas las tareas si no hay tablero_id
            $proyecto_id = null; // No hay proyecto_id si no hay tablero_id
        }
        
        return view('tareas.tablero_kanban', compact('tareas', 'tablero_id', 'estatuses', 'empleados', 'proyecto_id'));
    }

    /**
     * Actualiza el estado de una tarea (drag & drop).
     */
    public function updateStatus(Request $request, Tarea $tarea)
    {
        $request->validate([
            'fk_estatus_tarea' => 'required|exists:estatus_tareas,id',
        ]);

        $tarea->fk_estatus_tarea = $request->fk_estatus_tarea;
        $tarea->save();

        return response()->json(['success' => true, 'message' => 'Estado de tarea actualizado correctamente.']);
    }

    /**
     * Almacena una nueva tarea en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'notas' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fk_empleados' => 'required|exists:empleados,id',
            'fk_estatus_tarea' => 'required|exists:estatus_tareas,id',
            'fk_proyectos_id' => 'required|exists:proyectos,id',
            
            // --- CORRECCIÓN AQUÍ ---
            // Se cambió de 'nullable' a 'required'
            'fk_tablero_proyecto' => 'required|exists:tablero_proyecto,id', 
        ]);

        $tarea = Tarea::create([
            'titulo' => $request->titulo,
            'notas' => $request->notas,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'fk_empleados' => $request->fk_empleados,
            'fk_estatus_tarea' => $request->fk_estatus_tarea,
            'fk_proyectos' => $request->fk_proyectos_id,
            'fk_tablero_proyecto' => $request->fk_tablero_proyecto, // Esta línea recibe el ID del tablero
        ]);

        // Cargar las relaciones necesarias para la vista
        $tarea->load('empleado', 'estatusTarea');

        return response()->json(['success' => true, 'message' => 'Tarea creada correctamente.', 'tarea' => $tarea]);
    }

    /**
     * Muestra los datos de una tarea para la edición.
     */
    public function edit(Tarea $tarea)
    {
        // Devuelve la tarea como JSON para el modal
        return response()->json($tarea);
    }

    /**
     * Actualiza una tarea existente.
     */
    public function update(Request $request, Tarea $tarea)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'notas' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fk_empleados' => 'required|exists:empleados,id',
            'fk_estatus_tarea' => 'required|exists:estatus_tareas,id',
        ]);

        $tarea->update([
            'titulo' => $request->titulo,
            'notas' => $request->notas,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'fk_empleados' => $request->fk_empleados,
            'fk_estatus_tarea' => $request->fk_estatus_tarea,
        ]);

        // Recargar las relaciones para devolver la información completa
        $tarea->load('empleado', 'estatusTarea');

        return response()->json(['success' => true, 'message' => 'Tarea actualizada.', 'tarea' => $tarea]);
    }

    /**
     * Elimina una tarea permanentemente de la base de datos.
     */
    public function destroy(Tarea $tarea)
    {
        try {
            $tarea->delete();
            return response()->json(['success' => true, 'message' => 'Tarea eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar la tarea.'], 500);
        }
    }
}