<?php

namespace App\Http\Controllers;

use App\DTO\Task\TaskFormDTO;
use App\Http\Requests\Task\DestroyRequest;
use App\Http\Requests\Task\IndexRequest;
use App\Http\Requests\Task\MarkCompletedRequest;
use App\Http\Requests\Subtask\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TasksService;

class SubtaskController extends Controller
{
    public function __construct(protected TasksService $service)
    {
    }



    public function store(StoreRequest $request, Task $task): TaskResource
    {
        return new TaskResource($this->service->createSubtask($task, new TaskFormDTO(...$request->validated())));
    }


}
