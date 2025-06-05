<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class FulltextRequest extends FormRequest
{
    abstract public function hasFulltextSearch(): bool;
}
