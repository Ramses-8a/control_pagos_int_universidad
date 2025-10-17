<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    //lista
    public function index()
    {
        $proyectos = Proyecto::all(); 
        return view('proyectos.lista_proyectos', ['proyectos' => $proyectos]);
    }
    //form
  
    public function create()
    {
        
        return view('proyectos.formulario_proyectos');
    }

    //guardar
    public function store(Request $request)
    {
        // Valida los datos y obtiene solo los campos necesarios.
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
        ]);

        Proyecto::create($validatedData);

        //redirige a la lista
        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');
    }
    //mostar editar
    public function show(Proyecto $proyecto)
    {
        
        return redirect()->route('proyectos.edit', $proyecto);
    }

    //mostrar form para edit
    public function edit(Proyecto $proyecto)
    {
       
        return view('proyectos.formulario_proyectos', ['proyecto' => $proyecto]);
    }

    //act
    public function update(Request $request, Proyecto $proyecto)
    {
       
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'estatus' => 'required|in:activo,inactivo',
        ]);

        
        $proyecto->update($validatedData);
        
        // volver a lista
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    //cambiar estatus
    public function destroy(Proyecto $proyecto)
    {
        
        $proyecto->update(['estatus' => 'inactivo']);
        
        //volver a lista
        return redirect()->route('proyectos.index')->with('success', 'El estatus del proyecto ha sido cambiado a inactivo.');
    }
}
