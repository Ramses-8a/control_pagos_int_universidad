<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PuestoController extends Controller
{
    public function index()
    {
        $puestos = Puesto::orderBy('nombre')->get();
        return view('puestos.lista', compact('puestos'));
    }

    public function create()
    {
        return view('puestos.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:puestos',
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Puesto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('puestos.lista') 
            ->with('success', 'Puesto creado correctamente.');
    }

    public function edit($id)
    {
        $puesto = Puesto::findOrFail($id);
        return view('puestos.editar', compact('puesto'));
    }

public function update(Request $request, $id)
{
    $puesto = Puesto::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:255|unique:puestos,nombre,' . $id,
        'descripcion' => 'nullable|string',
        'estatus' => 'required|in:0,1' 
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $puesto->update($request->all());

    return redirect()->route('puestos.lista') 
        ->with('success', 'Puesto actualizado correctamente.');
}

   public function destroy($id)
{
    $puesto = Puesto::findOrFail($id);
    
    if ($puesto->empleados()->where('estatus', '1')->count() > 0) {
        return redirect()->route('puestos.lista') 
            ->with('error', 'No se puede desactivar el puesto porque tiene empleados activos asignados.');
    }

    $puesto->update(['estatus' => '0']);

    return redirect()->route('puestos.lista') 
        ->with('success', 'Puesto desactivado correctamente.');
}


public function activate($id)
{
    $puesto = Puesto::findOrFail($id);
    $puesto->update(['estatus' => '1']);

    return redirect()->route('puestos.lista') 
        ->with('success', 'Puesto activado correctamente.');
}
}
