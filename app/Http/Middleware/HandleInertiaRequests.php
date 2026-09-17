<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Plantilla Blade principal de Inertia.
     */
    protected $rootView = 'app';

    /**
     * Determina la versión actual de los assets.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Datos compartidos con todas las páginas de Inertia.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'auth' => [
                'user' => $request->user(),
            ],

            'flash' => [
                'success' => fn () =>
                    $request->session()->get('success'),

                'error' => fn () =>
                    $request->session()->get('error'),
            ],
        ];
    }
}