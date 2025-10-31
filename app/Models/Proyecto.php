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
        'estatus_proyecto_id', // Nombre de columna correcto
    ];

    
    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'fk_proyectos');
    }

    public function pagosEmpleados()
    {
        return $this->hasMany(PagosEmpleados::class, 'fk_proyectos');
    }

    /**
     * Un proyecto PERTENECE A un estatus.
     */
    public function estatusProyecto()
    {
        // Clave foránea correcta
        return $this->belongsTo(EstatusProyecto::class, 'estatus_proyecto_id');
    }
}