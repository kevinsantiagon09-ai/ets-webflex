<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_renders_the_react_page_with_login_access(): void
    {
        $this->get(route('home'))->assertInertia(fn (Assert $page) => $page
            ->component('Home')->where('loginUrl', route('login'))->where('auth.user', null));
    }

    public function test_login_renders_the_react_form(): void
    {
        $this->get(route('login'))->assertInertia(fn (Assert $page) => $page
            ->component('Login')->where('loginUrl', route('login.store')));
    }

    public function test_empty_inputs_return_individual_backend_errors(): void
    {
        $this->followingRedirects()->from(route('login'))->post(route('login.store'), [])
            ->assertInertia(fn (Assert $page) => $page
                ->component('Login')
                ->where('errors.email', 'Ingresa tu correo electrónico.')
                ->where('errors.password', 'Ingresa tu contraseña.'));
        $this->assertGuest();
    }

    public function test_invalid_email_is_rejected_by_the_backend(): void
    {
        $this->post(route('login.store'), ['email' => 'no-es-correo', 'password' => 'Segura!8'])
            ->assertInvalid(['email' => 'Ingresa un correo electrónico válido.']);
        $this->assertGuest();
    }

    public function test_active_administrator_can_login_and_access_admin(): void
    {
        $user = $this->createUser();

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Segura!8'])
            ->assertRedirectToRoute('admin');

        $this->assertAuthenticatedAs($user);
        $this->get(route('admin'))->assertInertia(fn (Assert $page) => $page->component('layouts/Admin'));
        $this->get(route('login'))->assertRedirectToRoute('admin');
    }

    #[TestWith(['Administrador', '0', false])]
    #[TestWith(['Editor', '1', false])]
    #[TestWith(['Administrador', '1', true])]
    public function test_ineligible_accounts_cannot_login_or_access_admin(string $role, string $active, bool $deleted): void
    {
        $user = $this->createUser($role, $active);
        if ($deleted) {
            $user->deleted_at = now();
            $user->save();
        }

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Segura!8'])
            ->assertInvalid(['email' => 'El correo o la contraseña son incorrectos, o la cuenta no tiene acceso.']);
        $this->assertGuest();
        $this->actingAs($user)->get(route('admin'))->assertForbidden();
        $this->post(route('banners.store'), [])->assertForbidden();
    }

    public function test_wrong_password_does_not_authenticate_or_flash_password(): void
    {
        $user = $this->createUser();

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Incorrecta!8'])
            ->assertInvalid(['email' => 'El correo o la contraseña son incorrectos, o la cuenta no tiene acceso.'])
            ->assertSessionMissing('_old_input.password');
        $this->assertGuest();
    }

    public function test_login_is_limited_after_five_failed_attempts(): void
    {
        $user = $this->createUser();
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Incorrecta!8']);
        }

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Segura!8'])
            ->assertInvalid('email');
        $this->assertGuest();

        $this->travel(61)->seconds();
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'Segura!8'])
            ->assertRedirectToRoute('admin');
        $this->assertAuthenticatedAs($user);
    }

    #[TestWith(['/admin'])]
    #[TestWith(['/admin/banners/create'])]
    #[TestWith(['/banner'])]
    public function test_admin_pages_redirect_guests_to_login(string $path): void
    {
        $this->get($path)->assertRedirectToRoute('login');
    }

    public function test_logout_ends_the_session(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)->post(route('logout'))->assertRedirectToRoute('home');

        $this->assertGuest();
        $this->get(route('admin'))->assertRedirectToRoute('login');
    }

    private function createUser(string $role = 'Administrador', string $active = '1'): User
    {
        $this->seed(RoleSeeder::class);

        return User::factory()->create([
            'role_id' => DB::table('roles')->where('name', $role)->value('id'),
            'is_active' => $active,
            'password' => 'Segura!8',
        ]);
    }
}
