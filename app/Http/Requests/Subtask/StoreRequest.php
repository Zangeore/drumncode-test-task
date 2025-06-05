<?php

namespace App\Http\Requests\Subtask;

use App\Enums\PolicyEnum;
use App\Enums\TaskStatusesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(TaskStatusesEnum::class)],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['required', 'string', 'min:1', 'max:65535'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can(PolicyEnum::UPDATE, $this->route('task'));
    }
}
