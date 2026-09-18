<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BannerControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_route_renders_the_admin_inertia_page(): void
    {
        $response = $this->get('/admin');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('layouts/Admin')
            ->url('/admin')
        );
    }

    public function test_authenticated_user_can_create_a_banner(): void
    {
        $user = User::factory()->create();
        $estadoId = $this->createEstadoFor($user);

        $this->actingAs($user)
            ->post(route('banners.store'), [
                'estado_id' => $estadoId,
                'image_path' => 'banners/home.jpg',
                'titulo' => 'Oferta de septiembre',
            ])
            ->assertRedirectToRoute('admin');

        $this->assertDatabaseHas('banners', [
            'estado_id' => $estadoId,
            'image_path' => 'banners/home.jpg',
            'titulo' => 'Oferta de septiembre',
            'user_id' => $user->id,
        ]);
    }

    public function test_store_returns_errors_for_each_invalid_banner_input(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('admin'))
            ->post(route('banners.store'), [
                'estado_id' => 'invalid',
                'image_path' => '',
                'titulo' => '',
            ])
            ->assertInvalid([
                'estado_id' => 'El estado seleccionado no es válido.',
                'image_path' => 'Ingresa la ruta de la imagen.',
                'titulo' => 'Ingresa un título.',
            ]);
    }

    public function test_guest_cannot_create_a_banner(): void
    {
        $this->post(route('banners.store'), [
            'estado_id' => 1,
            'image_path' => 'banners/home.jpg',
            'titulo' => 'Oferta de septiembre',
        ])->assertForbidden();
    }

    private function createEstadoFor(User $user): int
    {
        return DB::table('estados')->insertGetId([
            'uuid' => fake()->uuid(),
            'user_id' => $user->id,
            'activo' => 1,
            'inactivo' => 0,
            'softdelete' => 0,
        ]);
    }
}
