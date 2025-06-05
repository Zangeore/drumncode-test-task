<?php

namespace App\Http\Requests\Task;

use App\Enums\PolicyEnum;
use App\Enums\TaskStatusesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MarkCompletedRequest extends FormRequest
{
    public function rules(): array
    {
        return [
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('markCompleted', $this->route('task'));

    }
}
