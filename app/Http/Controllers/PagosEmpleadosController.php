<?php

namespace App\Http\Controllers;

use App\Models\PagosEmpleados;
use App\Models\Empleado;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagosEmpleadosController extends Controller
{
    public function index(Request $request)
{
    $query = PagosEmpleados::with(['empleado', 'proyecto']);
    
    // Filtro por proyecto
    if ($request->filled('proyecto')) {
        $query->where('fk_proyectos', $request->proyecto);
    }
    
    // Filtro por empleado
    if ($request->filled('empleado')) {
        $query->where('fk_empleados', $request->empleado);
    }
    
    //  Filtro por fecha de pago específica
    if ($request->filled('fecha_pago')) {
        $query->whereDate('fecha_pago', $request->fecha_pago);
    }
    
    $pagos = $query->orderBy('fecha_pago', 'desc')->get();
    
    // Datos para los filtros
    $proyectos = Proyecto::all();
    $empleados = Empleado::where('estatus', '1')->get();
    
    return view('pagos.lista', compact('pagos', 'proyectos', 'empleados'));
}

    public function create()
    {
        $empleados = Empleado::where('estatus', '1')->get();
        $proyectos = Proyecto::all();
        
        return view('pagos.create', compact('empleados', 'proyectos'));
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'monto' => 'required|numeric|min:0',
        'fecha_pago' => 'nullable|date', 
        'descripcion' => 'required|string|max:255',
        'fk_empleados' => 'required|exists:empleados,id',
        'fk_proyectos' => 'required|exists:proyectos,id'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    
    PagosEmpleados::create($request->all());

    return redirect()->route('pagos.lista')
        ->with('success', 'Pago registrado correctamente.');
}

    public function edit($id)
    {
        $pago = PagosEmpleados::findOrFail($id);
        $empleados = Empleado::where('estatus', '1')->get();
        $proyectos = Proyecto::all(); // ✅ Cambiado a all() temporalmente
        
        return view('pagos.editar', compact('pago', 'empleados', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $pago = PagosEmpleados::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'descripcion' => 'required|string|max:255',
            'fk_empleados' => 'required|exists:empleados,id',
            'fk_proyectos' => 'required|exists:proyectos,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pago->update($request->all());

        return redirect()->route('pagos.lista')
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        $pago = PagosEmpleados::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.lista')
            ->with('success', 'Pago eliminado correctamente.');
    }
}