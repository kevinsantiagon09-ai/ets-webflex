<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAccessAdmin() ?? false;
    }

    /** @return array<string, array<mixed>> */
    public function rules(): array
    {
        return [
            'primary_color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'text_color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'button_color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'font_family' => ['required', Rule::in(['Instrument Sans', 'Arial', 'Verdana', 'Georgia'])],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            '*.required' => 'Este campo es obligatorio.',
            '*.regex' => 'Ingresa un color hexadecimal de 6 dígitos, como #0f172a.',
            'font_family.in' => 'Selecciona una fuente válida.',
            'logo.image' => 'Selecciona una imagen válida.',
            'logo.mimes' => 'El logo debe ser JPG, PNG o WEBP.',
            'logo.max' => 'El logo no puede superar los 2 MB.',
        ];
    }
}
