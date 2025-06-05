<?php

namespace App\Repositories;

use App\DTO\Task\TaskFormDTO;
use App\Filters\FullTextSearchFilterWithRank;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskRepository
{
    public function indexForUser(User $user): Collection
    {
        return QueryBuilder::for($user->tasks())
            ->allowedFilters([
                AllowedFilter::custom('title', new FullTextSearchFilterWithRank()),
                AllowedFilter::custom('description', new FullTextSearchFilterWithRank()),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('priority'),
            ])
            ->allowedSorts([
                'priority',
                'created_at',
                'completed_at',
            ])->get();
    }

    public function getChildrenHierarchyByIds(array $ids)
    {
        $sql = '
WITH RECURSIVE task_tree AS (
    SELECT *
    FROM tasks
    WHERE parent_task_id IN (' . implode(',', $ids) . ')

    UNION ALL

    SELECT t.*
    FROM tasks t
    INNER JOIN task_tree tt ON t.parent_task_id = tt.id
)
SELECT *
FROM task_tree;';

        return DB::select($sql);
    }


    public function getParentChain(array $ids)
    {
        $sql = '
        WITH RECURSIVE parent_chain AS (
    SELECT *
    FROM tasks
    WHERE id IN (' . implode(',', $ids) . ')

    UNION ALL

    SELECT t.*
    FROM tasks t
    JOIN parent_chain pc ON pc.parent_task_id = t.id
)
SELECT *
FROM parent_chain;';

        return DB::select($sql);
    }

    public function create(TaskFormDTO $DTO): Model
    {
        return Task::create($DTO->toArray());
    }

    public function update(Task $task, TaskFormDTO $DTO)
    {
        return tap($task, fn($model) => $model->update($DTO->toSafeArray()));
    }

    public function destroy(Task $task)
    {
        $task->delete();
    }

    public function hasUncompletedChild(Task $task): bool
    {
        $sql = <<<SQL
        WITH RECURSIVE task_tree AS (
            SELECT id, parent_task_id, completed_at
            FROM tasks
            WHERE parent_task_id = :start_id

            UNION ALL

            SELECT t.id, t.parent_task_id, t.completed_at
            FROM tasks t
            INNER JOIN task_tree tt ON t.parent_task_id = tt.id
        )
        SELECT EXISTS (
            SELECT 1 FROM task_tree WHERE completed_at IS  NULL
        ) AS has_incomplete_descendants
    SQL;

        $result = DB::selectOne($sql, ['start_id' => $task->id]);

        return (bool)$result->has_incomplete_descendants;
    }
}
