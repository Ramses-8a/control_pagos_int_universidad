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
        $tipos_servicio = TipoServicio::where('estatus', 1)->orderBy('nombre')->get();
        return view('servicios.crear_servicios', compact('tipos_servicio'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:150',
            'costo' => 'nullable|numeric|min:0',
            'precio' => 'nullable|numeric|min:0',
            'fk_tipo_servicio' => 'required|exists:tipo_servicios,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Servicio::create($request->all());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    
    public function edit(Servicio $servicio)
    {
        $tipos_servicio = TipoServicio::where('estatus', 1)->orderBy('nombre')->get();
        return view('servicios.editar_servicios', compact('servicio', 'tipos_servicio'));
    }

    
    public function update(Request $request, Servicio $servicio)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:150',
            'costo' => 'nullable|numeric|min:0',
            'precio' => 'nullable|numeric|min:0',
            'estatus' => 'required|in:0,1',
            'fk_tipo_servicio' => 'required|exists:tipo_servicios,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $servicio->update($request->all());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}