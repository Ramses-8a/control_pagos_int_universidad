<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|max:255',
            'telefono' => 'string|max:10',
            'servicio_id' => 'required',
        ]);

        if ($request->servicio_id == '0') {
            $datos['servicio_id'] = null;
        }

        Contacto::create($datos);

        return back()->with('success', '¡Tu información ha sido enviada correctamente! Pronto nos pondremos en contacto.');
    }

    public function index()
    {
        $contactos = \App\Models\Contacto::with('servicio')
            ->latest()
            ->paginate(10);

        return view('clientes.inf_clientes', compact('contactos'));
    }
}
