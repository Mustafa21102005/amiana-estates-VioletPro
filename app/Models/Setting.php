<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['name', 'value'])]
class Setting extends Model
{
    /** @use HasFactory<\Database\Factories\SettingFactory> */
    use HasFactory;

    /** Every setting as ['name' => 'value'], cached so each page costs no extra query. */
    public static function map(): array
    {
        return Cache::rememberForever('settings', fn() => static::pluck('value', 'name')->all());
    }

    /** Read one setting. Usage anywhere: Setting::read('phone') */
    public static function read(string $name, ?string $default = null): ?string
    {
        return static::map()[$name] ?? $default;
    }

    /** Save several settings at once, then clear the cache so the site shows the new values. */
    public static function write(array $values): void
    {
        foreach ($values as $name => $value) {
            static::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        Cache::forget('settings');
    }
}
