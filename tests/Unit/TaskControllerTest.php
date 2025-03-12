<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Get token
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->token = $response->json('data.access_token');
    }

    public function test_can_get_all_tasks(): void
    {
        // Create some tasks
        Task::factory()->count(5)->create();

        // Send request
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/tasks');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'status',
                            'due_date',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ]
            ]);
    }

    public function test_can_create_task(): void
    {
        $taskData = [
            'name' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'To Do',
            'due_date' => now()->addDays(7)->toDateTimeString(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => [
                    'name' => 'Test Task',
                    'description' => 'This is a test task',
                    'status' => 'To Do',
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'To Do',
        ]);
    }

    public function test_can_show_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description,
                    'status' => $task->status,
                ]
            ]);
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create([
            'status' => 'To Do',
        ]);

        $updateData = [
            'status' => 'In Progress',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson('/api/tasks/' . $task->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => [
                    'id' => $task->id,
                    'status' => 'In Progress',
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'In Progress',
        ]);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/tasks/' . $task->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully',
            ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_cannot_create_task_with_invalid_data(): void
    {
        $taskData = [
            'name' => '', // Empty name should fail validation
            'status' => 'Invalid Status', // Invalid status should fail validation
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/tasks', $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'status']);
    }
}