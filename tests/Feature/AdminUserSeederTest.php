<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\TestWith;
use RuntimeException;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.admin' => [
            'name' => 'Administración',
            'email' => 'admin@example.com',
            'password' => 'Segura!8',
        ]]);
    }

    public function test_creates_an_active_administrator_with_a_hashed_password(): void
    {
        $this->seed(AdminUserSeeder::class);

        $user = User::query()->sole();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Administración',
            'email' => 'admin@example.com',
            'is_active' => '1',
            'role_id' => DB::table('roles')->where('name', 'Administrador')->value('id'),
        ]);
        $this->assertTrue(Hash::check('Segura!8', $user->password));
    }

    #[TestWith(['password', null])]
    #[TestWith(['password', 'Corta!'])]
    #[TestWith(['password', 'SinSimbolos123'])]
    #[TestWith(['password', 'abcdefgh '])]
    #[TestWith(['name', null])]
    #[TestWith(['email', null])]
    #[TestWith(['email', 'correo-invalido'])]
    public function test_rejects_invalid_configuration_without_creating_records(string $field, ?string $value): void
    {
        config(["auth.admin.$field" => $value]);

        try {
            $this->seed(AdminUserSeeder::class);
            $this->fail('Se esperaba un error de validación.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey($field, $exception->errors());
        }

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('roles', 0);
    }

    public function test_repeated_seeding_preserves_the_existing_password(): void
    {
        $this->seed(AdminUserSeeder::class);
        $user = User::query()->sole();
        config(['auth.admin.password' => 'OtraClave!9']);

        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'password' => $user->password]);
    }

    public function test_does_not_promote_an_existing_user_with_the_same_email(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.com']);

        try {
            $this->seed(AdminUserSeeder::class);
            $this->fail('Se esperaba un error por correo existente.');
        } catch (RuntimeException $exception) {
            $this->assertSame('ADMIN_EMAIL ya pertenece a otro usuario. Usa un correo diferente.', $exception->getMessage());
        }

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role_id' => null, 'password' => $user->password]);
    }
}
