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

    
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'fk_proyectos');
    }

    public function pagosEmpleados()
    {
        return $this->hasMany(PagosEmpleados::class, 'fk_proyectos');
    }

    //un proyecto tiene un estatus
     
    public function estatusProyecto()
    {
      
        return $this->belongsTo(EstatusProyecto::class, 'estatus_proyecto_id');
    }

    //ganancia estimada del proyecto

    public function calcularGanancia()
    {   
        return $this->precio - $this->costo;
    }
    //verifica si el proyecto tiene el estatus de completado

 
    public function estaCompletado(): bool
    {
        return $this->estatus_proyecto_id == 3;
    }
}
