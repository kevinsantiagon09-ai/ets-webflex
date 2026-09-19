<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Models\Estado;
use App\Models\Setting;
use App\Services\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    public function index(): Response
    {
        $setting = Setting::query()->oldest('id')->first();

        return Inertia::render('layouts/Admin', [
            'settingsUrl' => route('settings.store'),
            'setting' => $setting?->only(['site_name', 'primary_color', 'text_color', 'button_color', 'font_family']),
            'logoUrl' => $setting?->logo_path ? Storage::disk('public')->url($setting->logo_path) : null,
            'logoutUrl' => route('logout'),
            'estados' => Estado::select('id', 'activo', 'inactivo')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        return to_route('admin');
    }

    public function store(
        StoreBannerRequest $request,
        BannerService $bannerService
    ): RedirectResponse {
        $bannerService->create(
            $request->validated(),
            $request->user()
        );

        return to_route('admin')
            ->with('success', 'El banner fue creado correctamente.');
    }
}
