<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellidos',
        'puesto_id',
        'email', 
        'telefono',
        'estatus'
    ];

    // Relación con puesto
    public function puesto()
    {
        return $this->belongsTo(Puesto::class);
    }
    /**
     * Relación para los gastos
     */
    public function pagosEmpleados()
    {
        return $this->hasMany(pagosEmpleados::class);
    }
}