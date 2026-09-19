<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Models\Estado;
use App\Services\BannerService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('layouts/Admin');
    }

    public function create(): Response
{
    $estados = Estado::select(
        'id',
        'activo',
        'inactivo'
    )->get();

    return Inertia::render('banners/Create', [
        'estados' => $estados,
    ]);
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