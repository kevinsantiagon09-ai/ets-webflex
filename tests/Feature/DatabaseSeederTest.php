<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_seeder_creates_roles_and_states(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('roles', 2);
        $this->assertDatabaseHas('roles', ['name' => 'Administrador']);
        $this->assertDatabaseHas('roles', ['name' => 'Editor']);
        $this->assertDatabaseCount('estados', 2);
        $user = User::query()->firstOrFail();
        $this->assertDatabaseHas('estados', [
            'user_id' => $user->id,
            'activo' => 1,
            'inactivo' => 0,
            'softdelete' => 0,
        ]);
        $this->assertDatabaseHas('estados', [
            'user_id' => $user->id,
            'activo' => 0,
            'inactivo' => 1,
            'softdelete' => 0,
        ]);
    }

    public function test_repeated_seeding_preserves_existing_records(): void
    {
        $user = User::factory()->create();
        $this->seed();
        $role = (array) DB::table('roles')->where('name', 'Administrador')->first();
        $estado = (array) DB::table('estados')->where('activo', 1)->first();

        $this->seed();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
            'password' => $user->password,
        ]);
        $this->assertDatabaseCount('roles', 2);
        $this->assertDatabaseHas('roles', $role);
        $this->assertDatabaseCount('estados', 2);
        $this->assertDatabaseHas('estados', $estado);
    }
}
