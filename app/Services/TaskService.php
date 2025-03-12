<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * Get all tasks with optional filtering and searching
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getTasks(array $filters = []): LengthAwarePaginator
    {
        $query = Task::query();

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by due date
        if (!empty($filters['due_date_from'])) {
            $query->where('due_date', '>=', $filters['due_date_from']);
        }

        if (!empty($filters['due_date_to'])) {
            $query->where('due_date', '<=', $filters['due_date_to']);
        }

        // Search by name or description
        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Sort results
        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Paginate results
        $perPage = $filters['per_page'] ?? 10;
        return $query->paginate($perPage);
    }

    /**
     * Create a new task
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Get a task by ID
     *
     * @param int $id
     * @return Task|null
     */
    public function getTaskById(int $id): ?Task
    {
        return Task::find($id);
    }

    /**
     * Update an existing task
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * Delete a task
     *
     * @param Task $task
     * @return bool
     */
    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }
}