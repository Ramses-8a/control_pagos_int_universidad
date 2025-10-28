<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'costo',
        'precio',
        'estatus',
        'fk_tipo_servicio',
    ];

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'fk_tipo_servicio');
    }
}