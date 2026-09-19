<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->oldest('id')->firstOrFail();

        foreach ([[1, 0], [0, 1]] as [$activo, $inactivo]) {
            DB::table('estados')->updateOrInsert(
                ['activo' => $activo, 'inactivo' => $inactivo, 'softdelete' => 0],
                fn (bool $exists): array => $exists ? [] : [
                    'uuid' => (string) Str::uuid(),
                    'user_id' => $user->id,
                ],
            );
        }
    }
}
