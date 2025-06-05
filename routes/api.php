<?php

use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:sanctum'
], function () {
    Route::apiResource('tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('tasks/{task}/mark-completed', [TaskController::class, 'markCompleted']);
    Route::apiResource('tasks.subtasks', SubtaskController::class)
        ->only(['store']);
});
