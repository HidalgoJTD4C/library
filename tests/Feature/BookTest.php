<?php

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ==========================================
// PRUEBAS DE LA API (JSON)
// ==========================================

it('lista todos los libros en formato JSON', function () {
    // Creamos 3 libros de prueba usando la factory
    $books = Book::factory()->count(3)->create();

    $response = $this->getJson('/api/books');

    $response->assertOk()
        ->assertJsonCount(3) // Verifica que haya 3 libros
        ->assertJsonFragment(['title' => $books->first()->title]); // Verifica que el título esté ahí
});

it('filtra libros por categoría', function () {
    $fiction = Category::factory()->create(['name' => 'Ficción']);
    $science = Category::factory()->create(['name' => 'Ciencia']);

    // Creamos 2 de ficción y 3 de ciencia
    Book::factory()->count(2)->create(['category_id' => $fiction->id]);
    Book::factory()->count(3)->create(['category_id' => $science->id]);

    $response = $this->getJson('/api/books?category=' . $fiction->id);

    $response->assertOk()
        ->assertJsonCount(2) // Solo deben venir 2
        // Verifica la estructura de nuestro API Resource (category.id)
        ->assertJsonPath('0.category.id', $fiction->id);
});

it('crea un libro con datos válidos', function () {
    $category = Category::factory()->create();

    $payload = [
        'title' => 'Cien años de soledad',
        'author' => 'Gabriel García Márquez',
        'description' => 'Novela cumbre.',
        'published_year' => 1967,
        'category_id' => $category->id,
    ];

    $response = $this->postJson('/api/books', $payload);

    $response->assertCreated() // Status 201
        ->assertJsonPath('title', 'Cien años de soledad');

    // Verifica que realmente se guardó en la BD
    $this->assertDatabaseHas('books', ['title' => 'Cien años de soledad']);
});

it('falla al crear un libro con título muy corto', function () {
    $category = Category::factory()->create();

    $response = $this->postJson('/api/books', [
        'title' => 'ab', // Menos de 3 caracteres
        'author' => 'Autor',
        'published_year' => 2000,
        'category_id' => $category->id,
    ]);

    // Status 422 Unprocessable Entity
    $response->assertUnprocessable()
        ->assertJsonValidationErrors('title');
});

it('falla al crear un libro con categoría inexistente', function () {
    $response = $this->postJson('/api/books', [
        'title' => 'Libro válido',
        'author' => 'Autor',
        'published_year' => 2000,
        'category_id' => 9999, // ID que no existe
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('category_id');
});

it('actualiza un libro existente', function () {
    $book = Book::factory()->create(['title' => 'Título viejo']);
    $category = Category::factory()->create();

    $response = $this->putJson("/api/books/{$book->id}", [
        'title' => 'Título nuevo',
        'author' => $book->author,
        'published_year' => $book->published_year,
        'category_id' => $category->id,
    ]);

    $response->assertOk()
        ->assertJsonPath('title', 'Título nuevo');

    $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Título nuevo']);
});

it('devuelve 404 al actualizar un libro inexistente', function () {
    $category = Category::factory()->create();

    $response = $this->putJson('/api/books/999999', [
        'title' => 'Título',
        'author' => 'Autor',
        'published_year' => 2000,
        'category_id' => $category->id,
    ]);

    $response->assertNotFound(); // Status 404
});

it('elimina un libro existente', function () {
    $book = Book::factory()->create();

    $response = $this->deleteJson("/api/books/{$book->id}");

    $response->assertNoContent(); // Status 204
    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});

it('devuelve 404 al eliminar un libro inexistente', function () {
    $response = $this->deleteJson('/api/books/999999');

    $response->assertNotFound();
});

// ==========================================
// PRUEBAS DE LA VISTA WEB (BLADE)
// ==========================================

it('renderiza la vista web de libros', function () {
    Book::factory()->count(2)->create();

    // Hacemos una petición GET normal a la raíz 
    $response = $this->get('/');

    $response->assertOk()
        ->assertViewIs('books.index') // Verifica que cargue la vista correcta
        ->assertViewHas('books', fn ($viewBooks) => $viewBooks->count() === 2); // Verifica que le pase 2 libros a la vista
});
