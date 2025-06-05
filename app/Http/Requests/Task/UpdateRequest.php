<?php

namespace App\Http\Requests\Task;

use App\Enums\PolicyEnum;
use App\Enums\TaskStatusesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => [Rule::enum(TaskStatusesEnum::class)],
            'priority' => ['integer', 'min:1', 'max:5'],
            'title' => ['string', 'min:1', 'max:255'],
            'description' => ['string', 'min:1', 'max:65535'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can(PolicyEnum::UPDATE, $this->route('task'));
    }
}
