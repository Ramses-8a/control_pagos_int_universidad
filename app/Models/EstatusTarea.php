<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusTarea extends Model
{
    use HasFactory;

    protected $table = 'estatus_tareas'; // Especificar el nombre de la tabla si no sigue la convención de nombres de Laravel

    protected $fillable = [
        'nombre',
        'estatus',
    ];
}
