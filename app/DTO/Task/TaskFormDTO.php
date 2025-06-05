<?php

namespace App\DTO\Task;

class TaskFormDTO extends AbstractDTO
{
    public function __construct(
        public ?int $user_id = null,
        public ?string $status = null,
        public ?int $priority = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $completed_at = null,
        public ?int $parent_task_id = null
    )
    {
    }
}
