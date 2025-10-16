<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    public function create()
    {
        $puestos = Puesto::where('estatus', 'activo')->get();
        return view('empleados.create', compact('puestos'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'puesto_id' => 'required|exists:puestos,id',
            'email' => 'required|email|unique:empleados',
            'telefono' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Empleado::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'puesto_id' => $request->puesto_id,
            'email' => $request->email,
            'telefono' => $request->telefono
        ]);

        return redirect()->route('empleados.lista')
            ->with('success', 'Empleado registrado correctamente.');
    }

    public function lista()
    {
        $empleados = Empleado::with('puesto')
                     ->where('estatus', 'activo')
                     ->orderBy('nombre')
                     ->orderBy('apellidos')
                     ->get();
        return view('empleados.lista', compact('empleados'));
    }

    public function edit($id)
{
    $empleado = Empleado::with('puesto')->findOrFail($id);
    $puestos = Puesto::where('estatus', 'activo')->get();
    return view('empleados.editar', compact('empleado', 'puestos'));
}

public function update(Request $request, $id)
{
    $empleado = Empleado::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'puesto_id' => 'required|exists:puestos,id',
        'email' => 'required|email|unique:empleados,email,' . $id,
        'telefono' => 'nullable|string|max:20'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $empleado->update([
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'puesto_id' => $request->puesto_id,
        'email' => $request->email,
        'telefono' => $request->telefono
    ]);

    return redirect()->route('empleados.lista')
        ->with('success', 'Empleado actualizado correctamente.');
}

public function destroy($id)
{
    $empleado = Empleado::findOrFail($id);
    
    // En lugar de eliminar, cambiamos el estatus a inactivo
    $empleado->update(['estatus' => 'inactivo']);

    return redirect()->route('empleados.lista')
        ->with('success', 'Empleado desactivado correctamente.');
}
}
