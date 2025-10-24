<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableroProyecto extends Model
{
    use HasFactory;

    protected $table = 'tablero_proyecto';

    protected $casts = [
        'estatus' => 'boolean',
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'fk_proyecto',
        'estatus',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'fk_proyecto');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'fk_tablero_proyecto');
    }
}
