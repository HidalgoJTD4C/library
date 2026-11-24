<?php

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lista categorías con el conteo de libros', function () {
    $category = Category::factory()->create();
    Book::factory()->count(2)->create(['category_id' => $category->id]);

    $response = $this->getJson('/api/categories');

    $response->assertOk()
        ->assertJsonCount(1)
        // Verifica que el withCount('books') esté funcionando en el Resource
        ->assertJsonPath('0.books_count', 2);
});

it('crea una categoría válida', function () {
    $response = $this->postJson('/api/categories', ['name' => 'Poesía']);

    $response->assertCreated()
        ->assertJsonPath('name', 'Poesía');

    $this->assertDatabaseHas('categories', ['name' => 'Poesía']);
});

it('rechaza un nombre de categoría duplicado', function () {
    // Creamos una primero
    Category::factory()->create(['name' => 'Ficción']);

    // Intentamos crear otra igual
    $response = $this->postJson('/api/categories', ['name' => 'Ficción']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('name');
});

it('elimina una categoría sin libros asociados', function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson("/api/categories/{$category->id}");

    $response->assertNoContent();
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('NO elimina una categoría que tiene libros asociados', function () {
    // Creamos una categoría y le asignamos un libro
    $category = Category::factory()->create();
    Book::factory()->create(['category_id' => $category->id]);

    $response = $this->deleteJson("/api/categories/{$category->id}");

    // Debe devolver 409 Conflict
    $response->assertStatus(409)
        ->assertJsonPath('message', 'No se puede eliminar la categoría porque tiene libros asociados.');

    // La categoría NO debe haberse borrado de la BD
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});
    