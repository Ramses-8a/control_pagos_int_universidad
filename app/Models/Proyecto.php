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
        'estatus',
    ];

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'fk_proyectos');
    }

    public function pagosEmpleados()
    {
        return $this->hasMany(PagosEmpleados::class, 'fk_proyectos');
    }

    // public function servicios()
    // {
    //     return $this->hasMany(Servicio::class, 'fk_proyectos');
    // }
}
