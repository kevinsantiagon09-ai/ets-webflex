<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Administrador' => 'Administración del sitio web.',
            'Editor' => 'Edición del contenido del sitio web.',
        ];

        foreach ($roles as $name => $description) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                fn (bool $exists): array => $exists ? [] : [
                    'uuid' => (string) Str::uuid(),
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
