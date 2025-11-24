<?php

namespace App\Http\Controllers;

use App\Models\EstatusProyecto;
use App\Models\Proyecto;
use App\Models\Empleado; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with('estatusProyecto')->get(); 
        $estatuses = EstatusProyecto::all(); 
        return view('proyectos.lista_proyectos', compact('proyectos', 'estatuses'));
    }

    public function create()
    {
        $estatuses = EstatusProyecto::all();
        $empleados = Empleado::where('estatus', 1)->get(); 
        $proyecto = new Proyecto(); 
        
        return view('proyectos.formulario_proyectos', compact('estatuses', 'proyecto', 'empleados'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'estatus_proyecto_id' => 'required|exists:estatus_proyecto,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'concepto' => 'nullable|array',
            'monto' => 'nullable|array',
            'empleados' => 'nullable|array',
            'empleados.*' => 'exists:empleados,id',
        ]);

        $costoTotal = 0;
        if ($request->has('monto')) {
            $costoTotal = array_sum($request->monto);
        }

        DB::transaction(function () use ($validatedData, $request, $costoTotal) {
            $proyecto = Proyecto::create([
                'nombre' => $validatedData['nombre'],
                'precio' => $validatedData['precio'],
                'fecha_inicio' => $validatedData['fecha_inicio'],
                'fecha_fin' => $validatedData['fecha_fin'],
                'descripcion' => $validatedData['descripcion'],
                'estatus_proyecto_id' => $validatedData['estatus_proyecto_id'],
                'costo' => $costoTotal, 
            ]);

            if ($request->has('concepto')) {
                foreach ($request->concepto as $index => $concepto) {
                    if (!empty($concepto) && isset($request->monto[$index])) {
                        $proyecto->costos()->create([
                            'concepto' => $concepto,
                            'monto' => $request->monto[$index]
                        ]);
                    }
                }
            }

            if ($request->has('empleados')) {
                $proyecto->empleados()->sync($request->empleados);
            }
        });

        return redirect()->route('proyectos.index')->with('success', 'Proyecto creado exitosamente.');
    }
    
    public function edit(Proyecto $proyecto)
    {
        $estatuses = EstatusProyecto::all();
        $empleados = Empleado::where('estatus', 1)->get();
        $proyecto->load(['costos', 'empleados']); 
        
        return view('proyectos.formulario_proyectos', compact('proyecto', 'estatuses', 'empleados'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'estatus_proyecto_id' => 'required|exists:estatus_proyecto,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'concepto' => 'nullable|array',
            'monto' => 'nullable|array',
            'empleados' => 'nullable|array',
            'empleados.*' => 'exists:empleados,id',
        ]);
 
        $costoTotal = 0;
        if ($request->has('monto')) {
            $costoTotal = array_sum($request->monto);
        }

        DB::transaction(function () use ($validatedData, $request, $costoTotal, $proyecto) {
            $proyecto->update([
                'nombre' => $validatedData['nombre'],
                'precio' => $validatedData['precio'],
                'fecha_inicio' => $validatedData['fecha_inicio'],
                'fecha_fin' => $validatedData['fecha_fin'],
                'descripcion' => $validatedData['descripcion'],
                'estatus_proyecto_id' => $validatedData['estatus_proyecto_id'],
                'costo' => $costoTotal,
            ]);

            $proyecto->costos()->delete();

            if ($request->has('concepto')) {
                foreach ($request->concepto as $index => $concepto) {
                    if (!empty($concepto) && isset($request->monto[$index])) {
                        $proyecto->costos()->create([
                            'concepto' => $concepto,
                            'monto' => $request->monto[$index]
                        ]);
                    }
                }
            }

         
            $proyecto->empleados()->sync($request->empleados ?? []);
        });
        
        return redirect()->route('proyectos.index')->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function show(Proyecto $proyecto)
    {
        
        $proyecto->load(['estatusProyecto', 'costos', 'pagosEmpleados.empleado', 'empleados']); 
        return view('proyectos.show', compact('proyecto'));
    }


    public function actualizarEstatus(Request $request, Proyecto $proyecto)
    {
        $request->validate(['estatus_proyecto_id' => 'required|exists:estatus_proyecto,id']);
        $proyecto->estatus_proyecto_id = $request->estatus_proyecto_id;
        $proyecto->save();
        return response()->json(['success' => true, 'message' => 'Estatus actualizado.']);
    }

    public function destroy(Proyecto $proyecto)
    {
        $proyecto->update(['estatus_proyecto_id' => 2]);
        return redirect()->route('proyectos.index')->with('success', 'El estatus del proyecto ha sido cambiado a inactivo.');
    }
}