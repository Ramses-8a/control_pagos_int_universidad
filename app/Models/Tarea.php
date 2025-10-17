<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_proyectos',
        'titulo',
        'notas',
        'fecha_inicio',
        'fecha_fin',
        'fk_estatus_tarea',
        'fk_empleados',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'fk_proyectos');
    }

    public function estatusTarea()
    {
        return $this->belongsTo(EstatusTarea::class, 'fk_estatus_tarea');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'fk_empleados');
    }
}
