<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['site_name', 'primary_color', 'text_color', 'font_family', 'button_color'])]
class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory, SoftDeletes;
}
