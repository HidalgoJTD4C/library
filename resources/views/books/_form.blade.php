@csrf

<div class="mb-3">
    <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title"
           class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $book->title ?? '') }}" required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="author" class="form-label">Autor <span class="text-danger">*</span></label>
    <input type="text" name="author" id="author"
           class="form-control @error('author') is-invalid @enderror"
           value="{{ old('author', $book->author ?? '') }}" required>
    @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="published_year" class="form-label">Año de publicación <span class="text-danger">*</span></label>
    <input type="number" name="published_year" id="published_year"
           class="form-control @error('published_year') is-invalid @enderror"
           value="{{ old('published_year', $book->published_year ?? '') }}"
           min="1000" max="{{ date('Y') + 1 }}" required>
    @error('published_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
    <select name="category_id" id="category_id"
            class="form-select @error('category_id') is-invalid @enderror" required>
        <option value="">— Selecciona una categoría —</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', $book->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descripción</label>
    <textarea name="description" id="description" rows="4"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">{{ $submitLabel ?? 'Guardar' }}</button>
    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>
