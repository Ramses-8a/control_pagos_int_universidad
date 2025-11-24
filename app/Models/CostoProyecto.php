<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostoProyecto extends Model
{
    use HasFactory;

    protected $table = 'costo_proyecto';

  
    protected $fillable = [
        'proyecto_id',
        'concepto',
        'monto',
    ];

        public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}