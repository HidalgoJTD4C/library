@extends('layouts.app')

@section('title', 'Libros')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Libros</h1>
        <a href="{{ route('books.create') }}" class="btn btn-primary">+ Nuevo libro</a>
    </div>

    <form method="GET" action="{{ route('books.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @if (request('category'))
            <div class="col-auto">
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Limpiar filtro</a>
            </div>
        @endif
    </form>

    @if ($books->isEmpty())
        <div class="alert alert-info">No hay libros para mostrar.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle bg-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Año</th>
                        <th>Categoría</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>
                                <strong>{{ $book->title }}</strong>
                                @if ($book->description)
                                    <div class="small text-muted">{{ Str::limit($book->description, 60) }}</div>
                                @endif
                            </td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->published_year }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $book->category?->name ?? '—' }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('books.edit', $book->id) }}"
                                   class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este libro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
