<?php

namespace App\Enums\Utils;

trait UseEnumsTrait
{
    public static function keys(): array
    {
        return array_column(static::cases(),'name');
    }
    public static function values(): array
    {
        return array_column(static::cases(),'value');
    }

    public static function validation(): string
    {
        return 'in:'.implode(',',static::values());
    }
}
