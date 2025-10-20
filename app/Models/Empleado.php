<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apaterno',
        'amaterno',
        'correo', 
        'fk_puestos',
        'fk_periodo_pago',
        'estatus' 
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'fk_puestos');
    }

    public function periodoPago()
    {
        return $this->belongsTo(PeriodoPago::class, 'fk_periodo_pago');
    }

    
    public function scopeActivos($query)
    {
        return $query->where('estatus', '1');
    }


    
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apaterno} {$this->amaterno}";
    }

    
    public function desactivar()
    {
        $this->update(['status' => '0']);
    }

    
    public function activar()
    {
        $this->update(['status' => '1']);
    }
}