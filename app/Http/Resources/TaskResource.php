<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Task */
class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_task_id' => $this->parent_task_id,
            'status' => $this->status,
            'priority' => $this->priority,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'completed_at' => $this->completed_at,
            'children' => property_exists($this->resource, 'children') && $this->children !== null ? self::collection($this->children) : null,
            'parent' => property_exists($this->resource, 'parent') && $this->parent !== null ? new TaskResource($this->parent) : null,
        ];
    }
}
