<?php

namespace App\Http\Controllers;

use App\Models\EstatusProyecto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    
    public function index()
    {
        $proyectos = Proyecto::with('estatusProyecto')->get(); 
        $estatuses = EstatusProyecto::all(); // Necesario para el dropdown en la lista
        return view('proyectos.lista_proyectos', compact('proyectos', 'estatuses'));
    }

    public function create()
    {
        $estatuses = EstatusProyecto::all();
        return view('proyectos.formulario_proyectos', compact('estatuses'));
    }
    
    public function store(Request $request)
    {
        // Validamos usando 'estatus_proyecto_id'
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'estatus_proyecto_id' => 'required|exists:estatus_proyecto,id', 
        ]);

        Proyecto::create($validatedData);
        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');
    }
    
    public function show(Proyecto $proyecto)
    {
        $proyecto->load('estatusProyecto'); 
        return view('proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto)
    {
        $estatuses = EstatusProyecto::all();
        return view('proyectos.formulario_proyectos', compact('proyecto', 'estatuses'));
    }

    /**
     * Actualiza un proyecto existente en la base de datos.
     * --- ESTA FUNCIÓN CAUSABA EL ERROR ---
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        // CORRECCIÓN: Validamos 'estatus_proyecto_id'
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'estatus_proyecto_id' => 'required|exists:estatus_proyecto,id',
        ]);
 
        $proyecto->update($validatedData);
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Actualiza solo el estatus de un proyecto (vía AJAX).
     */
    public function actualizarEstatus(Request $request, Proyecto $proyecto)
    {
        // CORRECCIÓN: Validamos 'estatus_proyecto_id'
        $request->validate([
            'estatus_proyecto_id' => 'required|exists:estatus_proyecto,id'
        ]);

        // CORRECCIÓN: Usamos 'estatus_proyecto_id'
        $proyecto->estatus_proyecto_id = $request->estatus_proyecto_id;
        $proyecto->save();

        return response()->json([
            'success' => true,
            'message' => 'Estatus actualizado.'
        ]);
    }

    /**
     * Cambia el estatus de un proyecto a "Inactivo" (ID 2).
     */
    public function destroy(Proyecto $proyecto)
    {
        // CORRECCIÓN: Usamos 'estatus_proyecto_id'
        $proyecto->update(['estatus_proyecto_id' => 2]);
        
        return redirect()->route('proyectos.index')->with('success', 'El estatus del proyecto ha sido cambiado a inactivo.');
    }
}