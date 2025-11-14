<?php

namespace Tests\Feature;

use App\Models\PagosEmpleados;
use Tests\TestCase;

class PagosTest extends TestCase
{
    /** @test */
    public function test_pago_se_registra_con_datos_minimos()
    {
        $pago = new PagosEmpleados([
            'monto' => 1500.50,
            'descripcion' => 'Pago quincenal'
        ]);
        
        $this->assertEquals(1500.50, $pago->monto);
        $this->assertEquals('Pago quincenal', $pago->descripcion);
    }

    /** @test */
    public function test_pago_con_fecha()
    {
        $pago = new PagosEmpleados([
            'monto' => 2000.00,
            'fecha_pago' => '2024-01-15'
        ]);
        
        // Para campos date, Laravel usa Carbon, así que comparamos como string
        $this->assertEquals('2024-01-15', $pago->fecha_pago->format('Y-m-d'));
    }



    /** @test */
    public function test_formulario_pago_tiene_campos_requeridos()
    {
        $response = $this->get('/pagos/crear');
        $content = $response->content();
        
        $this->assertStringContainsString('fk_empleados', $content);
        $this->assertStringContainsString('monto', $content);
        $this->assertStringContainsString('descripcion', $content);
        $this->assertStringContainsString('fk_proyectos', $content);
    }
}