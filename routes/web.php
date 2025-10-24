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
Route::patch('/empleados/{id}/activar', [EmpleadoController::class, 'activate'])->name('empleados.activate');
Route::get('/empleados/{id}/historial', [EmpleadoController::class, 'historial'])->name('empleados.historial');

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
Route::patch('/puestos/{id}/activar', [PuestoController::class, 'activate'])->name('puestos.activate');


use App\Http\Controllers\PagosEmpleadosController;

// Rutas para pagos 
Route::get('/pagos', [PagosEmpleadosController::class, 'index'])->name('pagos.lista');
Route::get('/pagos/crear', [PagosEmpleadosController::class, 'create'])->name('pagos.create');
Route::post('/pagos', [PagosEmpleadosController::class, 'store'])->name('pagos.store');
Route::get('/pagos/{id}/editar', [PagosEmpleadosController::class, 'edit'])->name('pagos.editar');
Route::put('/pagos/{id}', [PagosEmpleadosController::class, 'update'])->name('pagos.actualizar');

Route::delete('/pagos/{id}/eliminar', [PagosEmpleadosController::class, 'destroy'])->name('pagos.eliminar');


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

Route::resource('servicios', ServicioController::class);

Route::resource('tipo_servicios', TipoServicioController::class);
