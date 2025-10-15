<?php

namespace App\Http\Controllers;

use App\Models\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoServicioController extends Controller
{
    // ... index(), create() y edit() no cambian ...
    public function index()
    {
        $tipos = TipoServicio::orderBy('nombre')->get();
        return view('tipo_servicios.lista_tipo_servicio', compact('tipos'));
    }

    public function create()
    {
        return view('tipo_servicios.crear_tipo_servicio');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:tipo_servicios',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TipoServicio::create($request->all());

        // CAMBIO AQUÍ
        return redirect()->route('tipo_servicios.lista')
            ->with('success', 'Tipo de servicio creado correctamente.');
    }

    public function edit(TipoServicio $tipoServicio)
    {
        return view('tipo_servicios.editar_tipo_servicio', compact('tipoServicio'));
    }

    public function update(Request $request, TipoServicio $tipoServicio)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:tipo_servicios,nombre,' . $tipoServicio->id,
            'estatus' => 'required|in:activo,inactivo'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tipoServicio->update($request->all());

        // CAMBIO AQUÍ
        return redirect()->route('tipo_servicios.lista')
            ->with('success', 'Tipo de servicio actualizado correctamente.');
    }

    public function destroy(TipoServicio $tipoServicio)
    {
        if ($tipoServicio->servicios()->count() > 0) {
            // CAMBIO AQUÍ
            return redirect()->route('tipo_servicios.lista')
                ->with('error', 'No se puede eliminar porque está en uso por uno o más servicios.');
        }

        $tipoServicio->delete();

        // CAMBIO AQUÍ
        return redirect()->route('tipo_servicios.lista')
            ->with('success', 'Tipo de servicio eliminado correctamente.');
    }
}
