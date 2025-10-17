<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController; 
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return view('../auth/login');
});

// Rutas para empleados
use App\Http\Controllers\EmpleadoController;

Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
Route::get('/empleados/lista', [EmpleadoController::class, 'lista'])->name('empleados.lista');
Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])->name('empleados.editar');
Route::put('/empleados/{id}/actualizar', [EmpleadoController::class, 'update'])->name('empleados.actualizar');
Route::delete('/empleados/{id}/eliminar', [EmpleadoController::class, 'destroy'])->name('empleados.eliminar');

// Rutas para puestos
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;

Route::get('/puestos/lista', [PuestoController::class, 'index'])->name('puestos.lista');
Route::get('/puestos/crear', [PuestoController::class, 'create'])->name('puestos.crear');
Route::post('/puestos/guardar', [PuestoController::class, 'store'])->name('puestos.guardar');
Route::get('/puestos/{id}/editar', [PuestoController::class, 'edit'])->name('puestos.editar');
Route::put('/puestos/{id}/actualizar', [PuestoController::class, 'update'])->name('puestos.actualizar');
Route::delete('/puestos/{id}/eliminar', [PuestoController::class, 'destroy'])->name('puestos.eliminar');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // crud para proyectos
    Route::resource('proyectos', ProyectoController::class);

    // Rutas para reportes
    Route::get('/reports', [ReporteController::class, 'index'])->name('reports.index');
    
});

require __DIR__.'/auth.php';
require __DIR__.'/auth.php';


//RUTAS DE CATÁLOGO DE SERVICIOS
Route::get('/catalogo_servicios', function () {return view('catalogo_servicios');})->middleware(['auth', 'verified'])->name('catalogo_servicios');

Route::get('/tipo_servicios/lista', [TipoServicioController::class, 'index'])->name('tipo_servicios.lista');
Route::get('/tipo_servicios/crear', [TipoServicioController::class, 'create'])->name('tipo_servicios.crear');
Route::post('/tipo_servicios/guardar', [TipoServicioController::class, 'store'])->name('tipo_servicios.guardar');

Route::get('/tipo_servicios/editar/{tipoServicio}', [TipoServicioController::class, 'edit'])->name('tipo_servicios.editar');
Route::put('/tipo_servicios/actualizar/{tipoServicio}', [TipoServicioController::class, 'update'])->name('tipo_servicios.actualizar');
Route::delete('/tipo_servicios/eliminar/{tipoServicio}', [TipoServicioController::class, 'destroy'])->name('tipo_servicios.eliminar');

Route::get('/servicios/lista', [ServicioController::class, 'index'])->name('servicios.lista');
Route::get('/servicios/crear', [ServicioController::class, 'create'])->name('servicios.crear');
Route::post('/servicios/guardar', [ServicioController::class, 'store'])->name('servicios.guardar');

Route::get('/servicios/editar/{servicio}', [ServicioController::class, 'edit'])->name('servicios.editar');
Route::put('/servicios/actualizar/{servicio}', [ServicioController::class, 'update'])->name('servicios.actualizar');
Route::delete('/servicios/eliminar/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.eliminar');
