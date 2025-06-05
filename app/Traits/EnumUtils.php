<?php

namespace App\Traits;

use UnitEnum;

trait EnumUtils
{
    public static function values(): array
    {
        return array_map(static fn (UnitEnum $value) => $value->value, static::cases());
    }

    public static function names(): array
    {
        return array_map(static fn (UnitEnum $value): string => $value->name, static::cases());
    }


}
