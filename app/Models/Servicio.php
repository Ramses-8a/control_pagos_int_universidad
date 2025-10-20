<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'costo',
        'estatus',
        'fk_tipo_servicio', 
    ];

    public function setEstatusAttribute($value)
    {
        $this->attributes['estatus'] = ($value === 'activo') ? 1 : 0;
    }

    public function getEstatusAttribute($value)
    {
        return $value == 1 ? 'activo' : 'inactivo';
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'fk_tipo_servicio');
    }
}