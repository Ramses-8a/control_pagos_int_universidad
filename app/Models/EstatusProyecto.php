<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstatusProyecto extends Model
{
    protected $table = 'estatus_proyecto'; // Especificar el nombre de la tabla si no sigue la convención de nombres de Laravel

    protected $fillable = [
        'nombre',
        'estatus',
    ];
}
