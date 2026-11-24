<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'min:3', 'max:255'],
            'author'         => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'published_year' => ['required', 'integer', 'digits:4', 'min:1000', 'max:' . (date('Y') + 1)],
            'category_id'    => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'El título es obligatorio.',
            'title.min'               => 'El título debe tener al menos 3 caracteres.',
            'author.required'         => 'El autor es obligatorio.',
            'published_year.required' => 'El año de publicación es obligatorio.',
            'published_year.integer'  => 'El año debe ser un número entero.',
            'published_year.digits'   => 'El año debe tener exactamente 4 dígitos.',
            'published_year.min'      => 'El año no puede ser menor a 1000.',
            'published_year.max'      => 'El año no puede ser mayor al próximo año.',
            'category_id.required'    => 'Debes seleccionar una categoría.',
            'category_id.exists'      => 'La categoría seleccionada no existe.',
        ];
    }

}
