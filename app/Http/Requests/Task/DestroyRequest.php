<?php

namespace App\Http\Requests\Task;

use App\Enums\PolicyEnum;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can(PolicyEnum::DESTROY, $this->route('task'));
    }
}
