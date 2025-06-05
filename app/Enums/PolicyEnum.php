<?php

namespace App\Enums;

enum PolicyEnum: string
{
    case VIEW = 'view';
    case STORE = 'store';
    case UPDATE = 'update';
    case DESTROY = 'destroy';

}
