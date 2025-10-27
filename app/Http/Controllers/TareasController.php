<?php

namespace App\Http\Controllers;

use App\Models\TableroProyecto;
use Illuminate\Http\Request;

class TareasController extends Controller
{
    public function index($tablero_id = null)
    {
        if ($tablero_id) {
            $tablero = TableroProyecto::with('tareas')->findOrFail($tablero_id);
            $tareas = $tablero->tareas;
        } else {
            $tareas = collect(); // O manejar todas las tareas si no hay tablero_id
        }
        return view('tareas.tablero_kanban', compact('tareas', 'tablero_id'));
    }
}
