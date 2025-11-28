<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Puesto;

class PuestoTest extends TestCase
{
    /** @test */
    public function test_puesto_tiene_nombre()
    {
        $puesto = new Puesto();
        $puesto->nombre = 'Jefe de proyecto';
        
        $this->assertEquals('Jefe de proyecto', $puesto->nombre);
    }

    /** @test */
    public function test_puesto_tiene_descripcion()
    {
        $puesto = new Puesto();
        $puesto->descripcion = 'Encargado de proyecto';
        
        $this->assertEquals('Encargado de proyecto', $puesto->descripcion);
    }

    /** @test */
    public function test_puesto_se_puede_crear()
    {
        $puesto = new Puesto([
            'nombre' => 'Desarrollador Backend',
            'descripcion' => 'Desarrollo de APIs'
        ]);
        
        $this->assertInstanceOf(Puesto::class, $puesto);
        $this->assertEquals('Desarrollador Backend', $puesto->nombre);
        $this->assertEquals('Desarrollo de APIs', $puesto->descripcion);
    }

    /** @test */
    public function test_validacion_nombre_obligatorio()
    {
        $puesto = new Puesto(['descripcion' => 'Sin nombre']);
        
        // Simular validación
        $this->assertEmpty($puesto->nombre);
        $this->assertNotEmpty($puesto->descripcion);
    }
}