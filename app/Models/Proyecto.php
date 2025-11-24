<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'costo',
        'precio',
        'estatus_proyecto_id',
    ];

   
    public function estatusProyecto()
    {
        return $this->belongsTo(EstatusProyecto::class, 'estatus_proyecto_id');
    }


    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'fk_proyectos');
    }

   
    public function costos()
    {
        return $this->hasMany(CostoProyecto::class, 'proyecto_id');
    }

   
    public function pagosEmpleados()
    {
        return $this->hasMany(PagosEmpleados::class, 'fk_proyectos');
    }
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_proyecto', 'proyecto_id', 'empleado_id');
    }
}