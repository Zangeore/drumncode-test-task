<?php

namespace App\DTO\Task;

use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractDTO implements Arrayable
{
    public function toArray()
    {
        return get_object_vars(...)($this);
    }

    public function toSafeArray()
    {
        return array_filter($this->toArray(), fn($value): bool => !is_null($value));
    }
}
