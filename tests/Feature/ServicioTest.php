<?php

namespace Tests\Feature;

use App\Models\Servicio;
use App\Models\TipoServicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicioTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function un_servicio_se_puede_crear_correctamente()
    {
        $tipo = TipoServicio::create(['nombre' => 'General', 'estatus' => 1]);
        $datos = [
            'nombre' => 'Formateo de PC',
            'precio' => 500.00,
            'costo' => 200.00,
            'descripcion' => 'Limpieza completa',
            'fk_tipo_servicio' => $tipo->id,
        ];
        $response = $this->post(route('servicios.store'), $datos);
        $response->assertRedirect(route('servicios.index')); 
        $this->assertDatabaseHas('servicios', ['nombre' => 'Formateo de PC']);
    }
    /** @test */
    public function el_nombre_y_precio_son_obligatorios()
    {
        $response = $this->post(route('servicios.store'), [
            'nombre' => '',
            'precio' => '',
        ]);
        $response->assertSessionHasErrors(['nombre', 'precio']);
    }
    /** @test */
    public function el_precio_no_puede_ser_negativo()
    {
        $tipo = TipoServicio::create(['nombre' => 'General', 'estatus' => 1]);
        $response = $this->post(route('servicios.store'), [
            'nombre' => 'Instalación',
            'descripcion' => 'Test',
            'precio' => -100.00,
            'fk_tipo_servicio' => $tipo->id,
        ]);
        $this->assertDatabaseMissing('servicios', ['precio' => -100.00]);
        $response->assertSessionHasErrors('precio');
    }
    /** @test */
    public function el_precio_debe_ser_un_numero()
    {
        $response = $this->post(route('servicios.store'), [
            'nombre' => 'Instalación',
            'precio' => 'veinte pesos',
        ]);
        $response->assertSessionHasErrors('precio');
    }
}