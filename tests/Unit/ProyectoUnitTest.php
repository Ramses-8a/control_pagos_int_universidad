<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Proyecto;
use App\Models\EstatusProyecto;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ProyectoUnitTest extends TestCase
{
    use RefreshDatabase;

    #[Test] 
    public function un_proyecto_pertenece_a_un_estatus_proyecto(): void
    {
        
        $estatus = EstatusProyecto::factory()->create([
            'nombre' => 'En Proceso'
        ]);
        
        $proyecto = Proyecto::factory()->create([
            'estatus_proyecto_id' => $estatus->id
        ]);

       
        $this->assertNotNull($proyecto->estatusProyecto);
        $this->assertInstanceOf(EstatusProyecto::class, $proyecto->estatusProyecto);    
        $this->assertEquals('En Proceso', $proyecto->estatusProyecto->nombre);
    }

   
    #[Test] 
    public function un_proyecto_puede_calcular_su_ganancia(): void
    {
        
        $proyecto = new Proyecto([
            'costo' => 1000.00,
            'precio' => 2500.50
        ]);

      
        $ganancia = $proyecto->calcularGanancia(); 
        $this->assertEquals(1500.50, $ganancia);
    }

    
    #[Test] 
    public function un_proyecto_puede_saber_si_esta_completado(): void
    {
       
        $estatusActivo = EstatusProyecto::factory()->create(['id' => 1, 'nombre' => 'Activo']);
        $estatusCompletado = EstatusProyecto::factory()->create(['id' => 3, 'nombre' => 'Completado']);

        $proyectoActivo = Proyecto::factory()->create([
            'estatus_proyecto_id' => $estatusActivo->id
        ]);
        
        $proyectoCompletado = Proyecto::factory()->create([
            'estatus_proyecto_id' => $estatusCompletado->id
        ]);

        $this->assertTrue($proyectoCompletado->estaCompletado());
        $this->assertFalse($proyectoActivo->estaCompletado());
    }
}