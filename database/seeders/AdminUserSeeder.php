<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = Validator::make([
            'name' => config('auth.admin.name'),
            'email' => config('auth.admin.email'),
            'password' => config('auth.admin.password'),
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', Password::min(8)->symbols(), 'regex:/[\p{P}\p{S}]/u'],
        ], [
            'name.required' => 'Configura ADMIN_NAME en el archivo .env.',
            'email.required' => 'Configura ADMIN_EMAIL en el archivo .env.',
            'email.email' => 'ADMIN_EMAIL debe ser un correo válido.',
            'password.required' => 'Configura ADMIN_PASSWORD en el archivo .env.',
            'password.min' => 'ADMIN_PASSWORD debe tener al menos 8 caracteres.',
            'password.symbols' => 'ADMIN_PASSWORD debe incluir al menos un carácter especial.',
            'password.regex' => 'ADMIN_PASSWORD debe incluir al menos un carácter especial; los espacios no cuentan.',
        ])->validate();

        DB::transaction(function () use ($data): void {
            $this->call(RoleSeeder::class);

            $roleId = DB::table('roles')->where('name', 'Administrador')->value('id');
            $user = User::query()->where('email', $data['email'])->first();

            if ($user !== null) {
                if ((int) $user->role_id !== (int) $roleId) {
                    throw new RuntimeException('ADMIN_EMAIL ya pertenece a otro usuario. Usa un correo diferente.');
                }

                return;
            }

            $user = new User;
            $user->uuid = (string) Str::uuid();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->role_id = $roleId;
            $user->is_active = '1';
            $user->save();
        });
    }
}
