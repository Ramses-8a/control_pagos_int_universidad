<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\TableroProyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TableroProyectoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function test_index_returns_view_with_data()
    {
        $response = $this->actingAs($this->user)->get(route('tableros.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tareas.lista_tableros');
        $response->assertViewHas('proyectos');
        $response->assertViewHas('tableros');
    }
}