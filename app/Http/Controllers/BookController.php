<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * GET /books  y  GET /api/books?category={id}
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        $books = $query->latest()->get();

        if ($request->is('api/*')) {
            return response()->json($books);
        }

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * GET /books/create  (solo web)
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('books.create', compact('categories'));
    }

    /**
     * POST /books  y  POST /api/books
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        if ($request->is('api/*')) {
            return response()->json($book->load('category'), 201);
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro creado correctamente.');
    }

    /**
     * GET /books/{id}/edit  (solo web)
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * PUT /books/{id}  y  PUT /api/books/{id}
     */
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->validated());

        if ($request->is('api/*')) {
            return response()->json($book->load('category'));
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro actualizado correctamente.');
    }

    /**
     * DELETE /books/{id}  y  DELETE /api/books/{id}
     */
    public function destroy(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        if ($request->is('api/*')) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro eliminado correctamente.');
    }
}
