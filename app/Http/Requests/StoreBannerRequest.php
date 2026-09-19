<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
 public function rules(): array
{
    return [
        'titulo' => [
            'required',
            'string',
            'max:150',
        ],

        'estado_id' => [
            'required',
            'exists:estados,id',
        ],

        'image' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
        ],
    ];
}
    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'estado_id.required' => 'Selecciona un estado.',
            'estado_id.integer' => 'El estado seleccionado no es válido.',
            'estado_id.exists' => 'El estado seleccionado no existe.',
            'image_path.required' => 'Ingresa la ruta de la imagen.',
            'image_path.max' => 'La ruta de la imagen no puede superar los 255 caracteres.',
            'titulo.required' => 'Ingresa un título.',
            'titulo.max' => 'El título no puede superar los 150 caracteres.',
        ];
    }
}
