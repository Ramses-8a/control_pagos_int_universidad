<?php

namespace Tests\Unit;

use App\Models\Servicio;
use PHPUnit\Framework\TestCase;

class ServicioTest extends TestCase
{
    
    public function puede_instanciar_servicio_con_atributos()
    {
        $servicio = new Servicio([
            'nombre' => 'Instalación de SO',
            'costo' => 150.50,
            'precio' => 300.00,
        ]);

        $this->assertEquals('Instalación de SO', $servicio->nombre);
        $this->assertEquals(150.50, $servicio->costo);
        $this->assertEquals(300.00, $servicio->precio);
    }

    
    public function verifica_calculo_de_ganancia()
    {
        $servicio = new Servicio();
        $servicio->precio = 500;
        $servicio->costo = 200;

        $ganancia = $servicio->precio - $servicio->costo;
        
        $this->assertEquals(300, $ganancia);
    }

    
    public function los_atributos_son_nulos_inicialmente()
    {
        $servicio = new Servicio();

        $this->assertNull($servicio->nombre);
        $this->assertNull($servicio->descripcion);
        $this->assertNull($servicio->costo);
        $this->assertNull($servicio->precio);
    }

    public function atributos_no_fillable_son_ignorados_en_mass_assignment()
    {
        $servicio = new Servicio();
        
        $datos = [
            'nombre' => 'Servicio de Red', 
            'id_secreto' => 12345         
        ];

        $servicio->fill($datos);

        $this->assertEquals('Servicio de Red', $servicio->nombre);

        $this->assertArrayNotHasKey('id_secreto', $servicio->getAttributes());
    }
}