<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Plantilla Blade principal que carga Inertia.
     *
     * Corresponde a:
     * resources/views/layouts/inertia.blade.php
     */
    protected $rootView = 'layouts.inertia';

    /**
     * Determina la versión actual de los assets.
     */
    public function version(Request $request): ?string
    {
        
        return parent::version($request);
    }

    /**
     * Datos compartidos globalmente con todas
     * las páginas de Inertia.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'auth' => [
                'user' => $request->user(),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}