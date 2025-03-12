<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['To Do', 'In Progress', 'Done'];

        return [
            'name' => $this->faker->sentence(rand(3, 6)),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }

    /**
     * Indicate that the task is to do.
     */
    public function todo(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'To Do',
            ];
        });
    }

    /**
     * Indicate that the task is in progress.
     */
    public function inProgress(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'In Progress',
            ];
        });
    }

    /**
     * Indicate that the task is done.
     */
    public function done(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Done',
            ];
        });
    }

    /**
     * Indicate that the task is overdue.
     */
    public function overdue(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'due_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            ];
        });
    }
}