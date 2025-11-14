<?php

namespace Tests\Feature;

use App\Models\Empleado;
use Tests\TestCase;

class EmpleadoTest extends TestCase
{
    /** @test */
    public function test_empleado_se_registra_con_datos_minimos()
    {
        $empleado = new Empleado([
            'nombre' => 'Juan',
            'apaterno' => 'Pérez',
            'correo' => 'juan@empresa.com'
        ]);
        
        $this->assertEquals('Juan', $empleado->nombre);
        $this->assertEquals('Pérez', $empleado->apaterno);
        $this->assertEquals('juan@empresa.com', $empleado->correo);
    }

    /** @test */
    public function test_empleado_tiene_nombre_completo()
    {
        $empleado = new Empleado([
            'nombre' => 'María',
            'apaterno' => 'López',
            'amaterno' => 'García'
        ]);
        
        $this->assertEquals('María López García', $empleado->nombre_completo);
    }

    
}