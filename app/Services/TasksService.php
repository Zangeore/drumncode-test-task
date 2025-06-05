<?php

namespace App\Services;

use App\DTO\Task\TaskFormDTO;
use App\Enums\TaskStatusesEnum;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Model;

class TasksService
{
    public function __construct(protected TaskRepository $repository)
    {
    }

    public function indexForUser(User $user, bool $hasFulltextSearch): array
    {
        $tasks = $this->repository->indexForUser($user)->toArray();
        if ($hasFulltextSearch) {
            return $this->fulltextIndexFormat($tasks);
        }

        return $this->buildTree($tasks);

    }

    protected function fulltextIndexFormat(array $tasks): array
    {
        $ids = array_column($tasks, 'id');
        $children = $this->repository->getChildrenHierarchyByIds($ids);
        $tasks = array_merge($tasks, $children);
        $tasks = $this->buildTree($tasks);

        $parents = $this->repository->getParentChain($ids);
        return $this->attachParentChains($tasks, $parents);
    }

    protected function attachParentChains(array $tasks, array $parents): array
    {
        $map = [];

        foreach ($parents as $parent) {
            $map[$parent->id] = (object)$parent;
        }

        foreach ($tasks as $task) {
            $current = $task;

            while (!empty($current->parent_task_id) && isset($map[$current->parent_task_id])) {
                $parent = $map[$current->parent_task_id];
                $current->parent = $parent;
                $current = $parent;
            }

            $current->parent = null;
        }

        return $tasks;
    }

    protected function buildTree(array $tasks): array
    {
        $map = [];
        $tree = [];

        foreach ($tasks as &$task) {
            $task = (object)$task;
            $task->children = [];

            $map[$task->id] = $task;
        }

        unset($task);
        foreach ($tasks as $task) {
            if ($task->parent_task_id && isset($map[$task->parent_task_id])) {
                $map[$task->parent_task_id]->children[] = $task;
            } else {
                $tree[] = $task;
            }
        }

        return $tree;
    }


    public function createForUser(User $user, TaskFormDTO $DTO): Model
    {
        $DTO->user_id = $user->id;
        return $this->repository->create($DTO);
    }

    public function update(Task $task, TaskFormDTO $DTO)
    {
        return $this->repository->update($task, $DTO);
    }

    public function destroy(Task $task): void
    {
        $this->repository->destroy($task);
    }

    public function markCompleted(Task $task): void
    {
        if (!$task->isCompleted()) {
            $this->repository->update($task, new TaskFormDTO(status: TaskStatusesEnum::DONE->value, completed_at: now()->toDateTimeString()));
        }
    }

    public function createSubtask(Task $task, TaskFormDTO $DTO): Model
    {
        $DTO->user_id = $task->user_id;
        $DTO->parent_task_id = $task->id;
        return $this->repository->create($DTO);

    }
}
