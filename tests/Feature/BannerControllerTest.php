<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BannerControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_route_renders_the_admin_inertia_page(): void
    {
        $user = $this->createAdmin();
        $estadoId = $this->createEstadoFor($user);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('layouts/Admin')
            ->url('/admin')
            ->has('estados', 1)
            ->where('estados.0.id', $estadoId)
            ->where('estados.0.activo', 1)
            ->where('estados.0.inactivo', 0)
        );
    }

    public function test_banner_creation_route_redirects_to_admin(): void
    {
        $this->actingAs($this->createAdmin())->get(route('banners.create'))
            ->assertRedirectToRoute('admin');
    }

    public function test_authenticated_user_can_create_a_banner(): void
    {
        $user = $this->createAdmin();
        $otherUser = User::factory()->create();
        $estadoId = $this->createEstadoFor($user);

        Storage::fake('public');
        $image = UploadedFile::fake()->image('home.jpg');

        $this->actingAs($user)
            ->post(route('banners.store'), [
                'user_id' => $otherUser->id,
                'estado_id' => $estadoId,
                'image' => $image,
                'titulo' => 'Oferta de septiembre',
            ])
            ->assertRedirectToRoute('admin');

        $this->assertDatabaseHas('banners', [
            'estado_id' => $estadoId,
            'image_path' => 'banners/'.$image->hashName(),
            'titulo' => 'Oferta de septiembre',
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseMissing('banners', ['user_id' => $otherUser->id]);
    }

    public function test_store_returns_errors_for_each_invalid_banner_input(): void
    {
        $user = $this->createAdmin();

        $this->actingAs($user)
            ->from(route('admin'))
            ->post(route('banners.store'), [
                'estado_id' => 'invalid',
                'image_path' => '',
                'titulo' => '',
            ])
            ->assertInvalid([
                'estado_id' => 'El estado seleccionado no existe.',
                'image',
                'titulo' => 'Ingresa un título.',
            ]);
    }

    public function test_guest_cannot_create_a_banner(): void
    {
        $this->post(route('banners.store'), [
            'estado_id' => 1,
            'image_path' => 'banners/home.jpg',
            'titulo' => 'Oferta de septiembre',
        ])->assertRedirectToRoute('login');
    }

    private function createAdmin(): User
    {
        $this->seed(RoleSeeder::class);

        return User::factory()->create([
            'role_id' => DB::table('roles')->where('name', 'Administrador')->value('id'),
            'is_active' => '1',
        ]);
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
