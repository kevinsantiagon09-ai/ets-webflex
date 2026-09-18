<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Services\BannerService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    /**
     * Mostrar la vista principal del administrador.
     */
    public function index(): Response
    {
        return Inertia::render('layouts/Admin');
    }

    /**
     * Guardar un nuevo banner.
     */
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