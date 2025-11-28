<?php

use App\Models\User;
use App\Models\Proyecto;
use App\Models\TableroProyecto;

// 1) Index debe renderizar la vista con variables
it('GET /tableros muestra la vista de lista de tableros', function () {
    /** @var \Tests\TestCase $this */ // <--- Añade esta línea
    $user = User::factory()->create();
    $this->actingAs($user);

    TableroProyecto::factory()->create(['estatus' => true]);

    $response = $this->get(route('tableros.index'));

    $response->assertStatus(200);
    $response->assertViewIs('tareas.lista_tableros');
    $response->assertViewHas('proyectos');
    $response->assertViewHas('tableros');
});

// 2) Store debe crear un tablero y responder JSON success
it('POST /tableros crea un tablero exitosamente', function () {
    /** @var \Tests\TestCase $this */ // <--- Añade esta línea
    $user = User::factory()->create();
    $this->actingAs($user);
    $proyecto = Proyecto::factory()->create();
    $payload = [
        'nombre' => 'Tablero Demo',
        'descripcion' => 'Prueba',
        'proyecto_id' => $proyecto->id,
    ];
    $response = $this->post(route('tableros.store'), $payload);
    $response->assertStatus(200)
             ->assertJson(['success' => true]);
    $this->assertDatabaseHas('tablero_proyecto', [
        'nombre' => 'Tablero Demo',
        'fk_proyecto' => $proyecto->id,
        'estatus' => 1,
    ]);
});

// 3) updateStatus debe actualizar el estatus correctamente
it('PUT /tableros/{tablero}/status actualiza estatus', function () {
    /** @var \Tests\TestCase $this */ // <--- Añade esta línea
    $user = User::factory()->create();
    $this->actingAs($user);

    $tablero = TableroProyecto::factory()->create(['estatus' => true]);

    $response = $this->put(route('tableros.updateStatus', ['tablero' => $tablero->id]), [
        'estado' => 0,
    ]);

    $response->assertStatus(200)
             ->assertJson(['success' => true]);

    $this->assertDatabaseHas('tablero_proyecto', [
        'id' => $tablero->id,
        'estatus' => 0,
    ]);
});