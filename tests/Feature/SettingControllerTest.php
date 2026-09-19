<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_settings_use_the_authenticated_user_and_ignore_submitted_ownership(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $otherUser = User::factory()->create();
        $logo = UploadedFile::fake()->image('logo.png');

        $this->actingAs($admin)->post(route('settings.store'), [
            ...$this->settingsData(),
            'user_id' => $otherUser->id,
            'uuid' => 'untrusted',
            'logo_path' => 'untrusted.png',
            'logo' => $logo,
        ])->assertRedirectToRoute('admin');

        $this->assertDatabaseHas('settings', [
            ...$this->settingsData(),
            'user_id' => $admin->id,
            'logo_path' => 'logos/'.$logo->hashName(),
        ]);
        $this->assertDatabaseMissing('settings', ['user_id' => $otherUser->id]);
        Storage::disk('public')->assertExists('logos/'.$logo->hashName());
    }

    public function test_saving_existing_settings_records_the_current_administrator_and_keeps_the_logo(): void
    {
        $setting = Setting::factory()->create(['logo_path' => 'logos/existing.png']);
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post(route('settings.store'), $this->settingsData())
            ->assertRedirectToRoute('admin');

        $this->assertDatabaseCount('settings', 1);
        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'uuid' => $setting->uuid,
            'user_id' => $admin->id,
            'logo_path' => 'logos/existing.png',
            ...$this->settingsData(),
        ]);
    }

    public function test_admin_receives_saved_settings_for_the_form(): void
    {
        $setting = Setting::factory()->create();

        $this->actingAs($this->createAdmin())->get(route('admin'))
            ->assertInertia(fn (Assert $page) => $page
                ->component('layouts/Admin')
                ->where('setting.site_name', $setting->site_name)
                ->where('setting.primary_color', $setting->primary_color)
                ->where('setting.font_family', $setting->font_family)
                ->where('settingsUrl', route('settings.store')));
    }

    public function test_invalid_settings_return_field_errors_without_changing_records(): void
    {
        Storage::fake('public');
        $setting = Setting::factory()->create();

        $this->actingAs($this->createAdmin())->post(route('settings.store'), [
            'primary_color' => 'red',
            'text_color' => '',
            'button_color' => '#xyzxyz',
            'font_family' => 'Invalid',
            'logo' => UploadedFile::fake()->create('document.pdf', 10, 'application/pdf'),
        ])->assertInvalid(['primary_color', 'text_color', 'button_color', 'font_family', 'logo']);

        $this->assertDatabaseHas('settings', ['id' => $setting->id, 'primary_color' => $setting->primary_color, 'user_id' => $setting->user_id]);
        Storage::disk('public')->assertDirectoryEmpty('logos');
    }

    public function test_guests_and_non_administrators_cannot_save_settings(): void
    {
        $this->post(route('settings.store'), $this->settingsData())->assertRedirectToRoute('login');
        $this->actingAs(User::factory()->create())->post(route('settings.store'), $this->settingsData())->assertForbidden();

        $this->assertDatabaseCount('settings', 0);
    }

    public function test_public_pages_receive_the_site_name_and_logo_without_internal_fields(): void
    {
        Setting::factory()->create(['site_name' => 'Mi empresa', 'logo_path' => 'logos/company.png']);

        foreach (['home', 'login'] as $route) {
            $this->get(route($route))->assertInertia(fn (Assert $page) => $page
                ->where('site.name', 'Mi empresa')
                ->where('site.primaryColor', '#0f172a')
                ->where('site.textColor', '#334155')
                ->where('site.buttonColor', '#0f172a')
                ->where('site.logoUrl', Storage::disk('public')->url('logos/company.png'))
                ->missing('site.user_id'));
        }
    }

    public function test_site_name_is_required_and_limited_in_length(): void
    {
        $this->actingAs($this->createAdmin());

        $this->post(route('settings.store'), [...$this->settingsData(), 'site_name' => ''])
            ->assertInvalid(['site_name' => 'Ingresa el nombre del sitio web.']);
        $this->post(route('settings.store'), [...$this->settingsData(), 'site_name' => str_repeat('a', 151)])
            ->assertInvalid(['site_name' => 'El nombre no puede superar los 150 caracteres.']);

        $this->assertDatabaseCount('settings', 0);
    }

    private function createAdmin(): User
    {
        $this->seed(RoleSeeder::class);

        return User::factory()->create([
            'is_active' => '1',
            'role_id' => DB::table('roles')->where('name', 'Administrador')->value('id'),
        ]);
    }

    /** @return array<string, string> */
    private function settingsData(): array
    {
        return ['site_name' => 'Empresa de ejemplo', 'primary_color' => '#112233', 'text_color' => '#223344', 'button_color' => '#334455', 'font_family' => 'Arial'];
    }
}
