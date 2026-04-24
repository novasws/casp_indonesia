<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['key', 'label', 'group', 'value', 'type'];

    /**
     * Get a site content value by key, with optional default.
     */
    public static function getValue(string $key, $default = null)
    {
        $content = static::where('key', $key)->first();
        if (!$content) return $default;

        if ($content->type === 'json') {
            return json_decode($content->value, true) ?? $default;
        }

        return $content->value;
    }

    /**
     * Set a site content value by key.
     */
    public static function setValue(string $key, $value): void
    {
        $content = static::where('key', $key)->first();
        if (!$content) return;

        if ($content->type === 'json') {
            $content->value = is_string($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE);
        } else {
            $content->value = $value;
        }

        $content->save();
    }
}
