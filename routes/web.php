<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController; 
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TareasController;

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

Route::get('Tareas/tablero/{tablero_id?}', [TareasController::class, 'index'])->name('tareas.index');
Route::patch('/tareas/{tarea}/update-status', [TareasController::class, 'updateStatus'])->name('tareas.updateStatus');
Route::post('/tareas', [TareasController::class, 'store'])->name('tareas.store');


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

    // Rutas para TableroProyecto
    Route::get('/tableros', [App\Http\Controllers\TableroProyectoController::class, 'index'])->name('tableros.index');
    Route::post('/tableros', [App\Http\Controllers\TableroProyectoController::class, 'store'])->name('tableros.store');
    Route::get('/tableros/{tablero}/edit', [App\Http\Controllers\TableroProyectoController::class, 'edit'])->name('tableros.edit');
    Route::put('/tableros/{tablero}', [App\Http\Controllers\TableroProyectoController::class, 'update'])->name('tableros.update');
    Route::put('/tableros/{tablero}/status', [App\Http\Controllers\TableroProyectoController::class, 'updateStatus'])->name('tableros.updateStatus');
    Route::delete('/tableros/{tablero}', [App\Http\Controllers\TableroProyectoController::class, 'destroy'])->name('tableros.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/auth.php';


//RUTAS DE CATÁLOGO DE SERVICIOS
Route::get('/catalogo_servicios', function () {return view('catalogo_servicios');})->middleware(['auth', 'verified'])->name('catalogo_servicios');

Route::resource('servicios', ServicioController::class);

Route::resource('tipo_servicios', TipoServicioController::class);
