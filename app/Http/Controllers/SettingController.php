<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function store(SaveSettingRequest $request): RedirectResponse
    {
        $setting = Setting::query()->oldest('id')->first() ?? new Setting;
        $setting->fill($request->safe()->except('logo'));
        $setting->uuid ??= (string) Str::uuid();
        $setting->user_id = $request->user()->id;

        if ($request->hasFile('logo')) {
            $setting->logo_path = $request->file('logo')->store('logos', 'public');
        }

        $setting->save();

        return to_route('admin')->with('success', 'La configuración fue guardada correctamente.');
    }
}
