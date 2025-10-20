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
        
        return view('proyectos.lista_proyectos', ['proyectos' => $proyectos]);
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto.
     */
    public function create()
    {
        $estatuses = EstatusProyecto::all();
        
        return view('proyectos.formulario_proyectos', compact('estatuses'));
    }
    
    /**
     * Guarda un nuevo proyecto en la base de datos.
     */
    public function store(Request $request)
    {
        // Valida usando 'fk_estatus_proyecto'
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'fk_estatus_proyecto' => 'required|exists:estatus_proyecto,id', 
        ]);

        Proyecto::create($validatedData);

        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');
    }
    
    /**
     * Muestra los detalles de un proyecto específico.
     * ESTA ES LA FUNCIÓN MODIFICADA
     */
    public function show(Proyecto $proyecto)
    {
        // Carga la relación para poder usarla en la vista 'show'
        $proyecto->load('estatusProyecto'); 
        
        // Retorna la nueva vista 'show.blade.php'
        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Muestra el formulario para editar un proyecto existente.
     */
    public function edit(Proyecto $proyecto)
    {
        $estatuses = EstatusProyecto::all();
        
        return view('proyectos.formulario_proyectos', compact('proyecto', 'estatuses'));
    }

    /**
     * Actualiza un proyecto existente en la base de datos.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        // Valida usando 'fk_estatus_proyecto'
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'fk_estatus_proyecto' => 'required|exists:estatus_proyecto,id',
        ]);
 
        $proyecto->update($validatedData);
        
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Cambia el estatus de un proyecto a "Inactivo" (ID 2).
     */
    public function destroy(Proyecto $proyecto)
    {
        // Actualiza usando 'fk_estatus_proyecto'
        $proyecto->update(['fk_estatus_proyecto' => 2]);
        
        return redirect()->route('proyectos.index')->with('success', 'El estatus del proyecto ha sido cambiado a inactivo.');
    }
}