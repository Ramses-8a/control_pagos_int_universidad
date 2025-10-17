<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagosEmpleados extends Model
{
    use HasFactory;

    protected $table = 'pagos_empleados';

    protected $fillable = [
        'monto',
        'fecha_pago',
        'descripcion',
        'fk_empleados',
        'fk_proyectos',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'fk_empleados');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'fk_proyectos');
    }
}
