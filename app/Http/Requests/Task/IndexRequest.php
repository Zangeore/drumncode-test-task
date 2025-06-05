<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskStatusesEnum;
use App\Http\Requests\FulltextRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FulltextRequest
{
    public function rules(): array
    {
        return [
            'filter.status' => [Rule::enum(TaskStatusesEnum::class)],
            'filter.priority' => ['int', 'min:1', 'max:5'],
            'filter.title' => ['string'],
            'filter.description' => ['string'],
            'sort' => ['string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function hasFulltextSearch(): bool
    {
        return $this->has('filter.description') || $this->has('filter.title');
    }
}
