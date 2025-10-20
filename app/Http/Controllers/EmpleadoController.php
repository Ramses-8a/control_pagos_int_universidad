<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Puesto;
use App\Models\PeriodoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    public function create()
    {
        $puestos = Puesto::where('estatus', '1')->get();
        $periodosPago = PeriodoPago::where('estatus', '1')->get();
        return view('empleados.create', compact('puestos', 'periodosPago'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'required|string|max:255',
            'correo' => 'required|email|unique:empleados',
            'fk_puestos' => 'required|exists:puestos,id',
            'fk_periodo_pago' => 'required|exists:periodo_pago,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Empleado::create([
            'nombre' => $request->nombre,
            'apaterno' => $request->apaterno,
            'amaterno' => $request->amaterno,
            'correo' => $request->correo,
            'fk_puestos' => $request->fk_puestos,
            'fk_periodo_pago' => $request->fk_periodo_pago,
            'estatus' => '1',
        ]);

        return redirect()->route('empleados.lista')
            ->with('success', 'Empleado registrado correctamente.');
    }

    public function lista()
    {
        
        $empleados = Empleado::with(['puesto', 'periodoPago'])
                     ->orderBy('nombre')
                     ->get(); 
        return view('empleados.lista', compact('empleados'));
    }

    public function edit($id)
    {
        $empleado = Empleado::with(['puesto', 'periodoPago'])->findOrFail($id);
        $puestos = Puesto::where('estatus', '1')->get();
        $periodosPago = PeriodoPago::where('estatus', '1')->get();
        return view('empleados.editar', compact('empleado', 'puestos', 'periodosPago'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'required|string|max:255',
            'correo' => 'required|email|unique:empleados,correo,' . $id,
            'fk_puestos' => 'required|exists:puestos,id',
            'fk_periodo_pago' => 'required|exists:periodo_pago,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $empleado->update([
            'nombre' => $request->nombre,
            'apaterno' => $request->apaterno,
            'amaterno' => $request->amaterno,
            'correo' => $request->correo,
            'fk_puestos' => $request->fk_puestos,
            'fk_periodo_pago' => $request->fk_periodo_pago,
        ]);

        return redirect()->route('empleados.lista')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        
       
        $empleado->update(['estatus' => '0']);

        return redirect()->route('empleados.lista')
            ->with('success', 'Empleado desactivado correctamente.');
    }

    public function activate($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update(['estatus' => '1']);

        return redirect()->route('empleados.lista')
            ->with('success', 'Empleado reactivado correctamente.');
    }
}