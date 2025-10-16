<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
});

require __DIR__.'/auth.php';