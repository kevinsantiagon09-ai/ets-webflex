<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Setting> */
class SettingFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'site_name' => 'Mi sitio web',
            'uuid' => fake()->uuid(),
            'user_id' => User::factory(),
            'primary_color' => '#0f172a',
            'text_color' => '#334155',
            'button_color' => '#0f172a',
            'font_family' => 'Instrument Sans',
        ];
    }
}
