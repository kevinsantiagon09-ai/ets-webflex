<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.string' => 'Ingresa un correo electrónico válido.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.max' => 'El correo no puede superar los 255 caracteres.',
            'password.required' => 'Ingresa tu contraseña.',
            'password.string' => 'Ingresa una contraseña válida.',
        ];
    }

    public function authenticate(): void
    {
        $key = 'login:'.hash('sha256', mb_strtolower($this->string('email')->toString()).'|'.$this->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Demasiados intentos. Intenta de nuevo en '.RateLimiter::availableIn($key).' segundos.',
            ]);
        }

        if (! Auth::attemptWhen($this->validated(), fn (User $user): bool => $user->canAccessAdmin())) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => 'El correo o la contraseña son incorrectos, o la cuenta no tiene acceso.',
            ]);
        }

        RateLimiter::clear($key);
    }
}
