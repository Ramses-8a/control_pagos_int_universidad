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

    public function create()
    {
        $estatuses = EstatusProyecto::all();
        return view('proyectos.formulario_proyectos', compact('estatuses'));
    }
    
    public function store(Request $request)
    {
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
    
    public function edit(Proyecto $proyecto)
    {
        $estatuses = EstatusProyecto::all();
        return view('proyectos.formulario_proyectos', compact('proyecto', 'estatuses'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
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

    public function destroy(Proyecto $proyecto)
    {
       
        $proyecto->update(['fk_estatus_proyecto' => 2]); 
        
        return redirect()->route('proyectos.index')->with('success', 'El estatus del proyecto ha sido cambiado a inactivo.');
    }

    public function show(Proyecto $proyecto)
    {
        return redirect()->route('proyectos.edit', $proyecto);
    }
}