<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskService $taskService;

    /**
     * Create a new controller instance.
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'status',
            'due_date_from',
            'due_date_to',
            'search',
            'sort_by',
            'sort_direction',
            'per_page'
        ]);

        $tasks = $this->taskService->getTasks($filters);

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified task.
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $updatedTask = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $updatedTask
        ]);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $this->taskService->deleteTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}