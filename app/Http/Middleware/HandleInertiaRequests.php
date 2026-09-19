<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

            'site' => function (): array {
                $setting = Setting::query()->oldest('id')->first();

                return [
                    'name' => $setting?->site_name ?: 'ETS WebFlex',
                    'primaryColor' => $setting?->primary_color ?: '#0f172a',
                    'textColor' => $setting?->text_color ?: '#334155',
                    'buttonColor' => $setting?->button_color ?: '#0f172a',
                    'logoUrl' => $setting?->logo_path ? Storage::disk('public')->url($setting->logo_path) : null,
                ];
            },

            'auth' => [
                'user' => $request->user(),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
