<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * GET /categories  y  GET /api/categories
     */
    public function index(Request $request)
    {
        $categories = Category::withCount('books')->orderBy('name')->get();

        if ($request->is('api/*')) {
            return response()->json($categories);
        }

        return view('categories.index', compact('categories'));
    }

    /**
     * POST /categories  y  POST /api/categories
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->is('api/*')) {
            return response()->json($category, 201);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * DELETE /categories/{id}  y  DELETE /api/categories/{id}
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($category->books()->exists()) {
            $message = 'No se puede eliminar la categoría porque tiene libros asociados.';

            if ($request->is('api/*')) {
                return response()->json(['message' => $message], 409);
            }

            return redirect()
                ->route('categories.index')
                ->with('error', $message);
        }

        $category->delete();

        if ($request->is('api/*')) {
            return response()->json(null, 204);
        }

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
