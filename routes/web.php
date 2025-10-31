<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController; 
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TareasController;
use App\Http\Controllers\DashboardController;
use App\Models\Servicio;

// Controladores
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\PagosEmpleadosController;
use App\Http\Controllers\TableroProyectoController; // Importación añadida para claridad

// web.php
Route::get('/', function () {
    $serviciosDisponibles = Servicio::with('tipoServicio')
        ->where('estatus', 1)
        ->whereHas('tipoServicio', function ($query) {
            $query->where('estatus', 1);
        })
        ->orderBy('nombre')
        ->get();
    
    return view('principal', compact('serviciosDisponibles'));
})->name('home');

// Rutas para empleados
Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');
Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
Route::get('/empleados/lista', [EmpleadoController::class, 'lista'])->name('empleados.lista');
Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])->name('empleados.editar');
Route::put('/empleados/{id}/actualizar', [EmpleadoController::class, 'update'])->name('empleados.actualizar');
Route::delete('/empleados/{id}/eliminar', [EmpleadoController::class, 'destroy'])->name('empleados.eliminar');
Route::patch('/empleados/{id}/activar', [EmpleadoController::class, 'activate'])->name('empleados.activate');
Route::get('/empleados/{id}/historial', [EmpleadoController::class, 'historial'])->name('empleados.historial');

// Rutas para puestos
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

// Rutas para pagos 
Route::get('/pagos', [PagosEmpleadosController::class, 'index'])->name('pagos.lista');
Route::get('/pagos/crear', [PagosEmpleadosController::class, 'create'])->name('pagos.create');
Route::post('/pagos', [PagosEmpleadosController::class, 'store'])->name('pagos.store');
Route::get('/pagos/{id}/editar', [PagosEmpleadosController::class, 'edit'])->name('pagos.editar');
Route::put('/pagos/{id}', [PagosEmpleadosController::class, 'update'])->name('pagos.actualizar');
Route::delete('/pagos/{id}/eliminar', [PagosEmpleadosController::class, 'destroy'])->name('pagos.eliminar');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // crud para proyectos
    Route::resource('proyectos', ProyectoController::class);

   
    // Actualización de estatus desde la lista de proyectos
    Route::patch('/proyectos/{proyecto}/actualizar-estatus', [ProyectoController::class, 'actualizarEstatus'])
        ->name('proyectos.actualizarEstatus');

    // Rutas para reportes
    Route::get('/reports', [ReporteController::class, 'index'])->name('reports.index');

    // Rutas para TableroProyecto
    Route::get('/tableros', [TableroProyectoController::class, 'index'])->name('tableros.index');
    Route::post('/tableros', [TableroProyectoController::class, 'store'])->name('tableros.store');
    Route::get('/tableros/{tablero}/edit', [TableroProyectoController::class, 'edit'])->name('tableros.edit');
    Route::put('/tableros/{tablero}', [TableroProyectoController::class, 'update'])->name('tableros.update');
    Route::put('/tableros/{tablero}/status', [TableroProyectoController::class, 'updateStatus'])->name('tableros.updateStatus');
    Route::delete('/tableros/{tablero}', [TableroProyectoController::class, 'destroy'])->name('tableros.destroy');
});

require __DIR__.'/auth.php';
// Se eliminó la línea duplicada de 'require auth.php'

//RUTAS DE CATÁLOGO DE SERVICIOS
Route::get('/catalogo_servicios', function () {return view('catalogo_servicios');})->middleware(['auth', 'verified'])->name('catalogo_servicios');

Route::resource('servicios', ServicioController::class);
Route::resource('tipo_servicios', TipoServicioController::class);