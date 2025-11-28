<?php
// app/Models/EstatusProyecto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusProyecto extends Model
{
    use HasFactory;
    
    protected $table = 'estatus_proyecto';
    
    // Un estatus puede tener muchos proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'estatus_proyecto_id');
    }
}