<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoPago extends Model
{
    use HasFactory;

    protected $table = 'periodo_pago';

    protected $fillable = [
        'nombre',
        'estatus'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'fk_periodo_pago');
    }

    public function scopeActivos($query)
    {
        return $query->where('estatus', '1');
    }
}