<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function __construct(protected TaskRepository $taskRepository)
    {
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isOwner($user, $task);
    }

    public function destroy(User $user, Task $task): bool
    {
        return !$task->isCompleted() && $this->isOwner($user, $task);
    }

    public function markCompleted(User $user, Task $task): bool
    {
        return $this->isOwner($user, $task) && !$this->taskRepository->hasUncompletedChild($task);
    }

    protected function isOwner(User $user, Task $task): bool
    {
        return $task->user_id === $user->id;
    }

}
