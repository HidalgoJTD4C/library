@extends('layouts.app')

@section('title', 'Nuevo libro')

@section('content')
    <h1 class="h3 mb-3">Nuevo libro</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('books.store') }}">
                @include('books._form', ['submitLabel' => 'Crear libro'])
            </form>
        </div>
    </div>
@endsection
