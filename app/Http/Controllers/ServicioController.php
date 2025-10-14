<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('tipoServicio')->orderBy('nombre')->get();
        return view('servicios.lista_servicios', compact('servicios'));
    }

    public function create()
    {
        $tipos_servicio = TipoServicio::where('estatus', 'activo')->orderBy('nombre')->get();
        return view('servicios.crear_servicios', compact('tipos_servicio'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'fk_tipo_servicio' => 'required|exists:tipo_servicios,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Servicio::create($request->all());

        // CAMBIO AQUÍ
        return redirect()->route('servicios.lista')
            ->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        $tipos_servicio = TipoServicio::where('estatus', 'activo')->orderBy('nombre')->get();
        return view('servicios.editar_servicios', compact('servicio', 'tipos_servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'estatus' => 'required|in:activo,inactivo',
            'fk_tipo_servicio' => 'required|exists:tipo_servicios,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $servicio->update($request->all());

        // CAMBIO AQUÍ
        return redirect()->route('servicios.lista')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        // CAMBIO AQUÍ
        return redirect()->route('servicios.lista')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}
