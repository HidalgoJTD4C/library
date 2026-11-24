@extends('layouts.app')

@section('title', 'Editar libro')

@section('content')
    <h1 class="h3 mb-3">Editar libro: <em>{{ $book->title }}</em></h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('books.update', $book->id) }}">
                @method('PUT')
                @include('books._form', ['submitLabel' => 'Actualizar libro'])
            </form>
        </div>
    </div>
@endsection
