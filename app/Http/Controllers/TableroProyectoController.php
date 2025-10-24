<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\TableroProyecto;
use Illuminate\Http\Request;

class TableroProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::all();
        $tableros = TableroProyecto::with('proyecto')->where('estatus', true)->get();
        return view('tareas.lista_tableros', compact('proyectos', 'tableros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $tablero = TableroProyecto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fk_proyecto' => $request->proyecto_id,
            'estatus' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Tablero creado correctamente.']);
    }

    public function destroy(TableroProyecto $tablero)
    {
        $tablero->delete();
        return response()->json(['success' => true, 'message' => 'Tablero eliminado correctamente.']);
    }

    public function edit(TableroProyecto $tablero)
    {
        return response()->json($tablero);
    }

    public function update(Request $request, TableroProyecto $tablero)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $tablero->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fk_proyecto' => $request->proyecto_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Tablero actualizado correctamente.']);
    }

    public function updateStatus(Request $request, TableroProyecto $tablero)
    {
        $request->validate([
            'estado' => 'required|in:0,1',
        ]);

        $tablero->estatus = (bool)$request->estado;
        $tablero->save();

        return response()->json(['success' => true, 'message' => 'Estado del tablero actualizado correctamente.']);
    }
}
