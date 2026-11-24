@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
    <h1 class="h3 mb-3">Categorías</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Nueva categoría</h2>
            <form method="POST" action="{{ route('categories.store') }}" class="row g-2 align-items-start">
                @csrf
                <div class="col-md-6">
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="Nombre de la categoría" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle bg-white">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Libros asociados</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $category->books_count }}</span>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta categoría?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        @if ($category->books_count > 0)
                                            disabled
                                            title="Tiene libros asociados"
                                        @endif>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No hay categorías.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
