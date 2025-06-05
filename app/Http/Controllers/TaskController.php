<?php

namespace App\Http\Controllers;

use App\DTO\Task\TaskFormDTO;
use App\Http\Requests\Task\DestroyRequest;
use App\Http\Requests\Task\IndexRequest;
use App\Http\Requests\Task\MarkCompletedRequest;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TasksService;
use Dedoc\Scramble\Attributes\QueryParameter;

class TaskController extends Controller
{
    public function __construct(protected TasksService $service)
    {
    }

    #[QueryParameter('sort',  type: 'string', example: "created_at or -created_at or created_at,-completed_at")]
    public function index(IndexRequest $request)
    {
        return TaskResource::collection($this->service->indexForUser($request->user(), $request->hasFulltextSearch()));
    }

    public function store(StoreRequest $request)
    {
        return new TaskResource($this->service->createForUser($request->user(), new TaskFormDTO(...$request->validated())));
    }


    public function update(UpdateRequest $request, Task $task)
    {
        return new TaskResource($this->service->update($task, new TaskFormDTO(...$request->validated())));
    }

    public function destroy(DestroyRequest $request, Task $task)
    {
        $this->service->destroy($task);
        return response()->noContent();
    }

    public function markCompleted(MarkCompletedRequest $request, Task $task)
    {
        $this->service->markCompleted($task);
        return response()->noContent();
    }
}
