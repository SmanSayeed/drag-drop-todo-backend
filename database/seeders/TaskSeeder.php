<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some to-do tasks
        Task::factory()
            ->count(5)
            ->todo()
            ->create();

        // Create some in-progress tasks
        Task::factory()
            ->count(3)
            ->inProgress()
            ->create();

        // Create some done tasks
        Task::factory()
            ->count(4)
            ->done()
            ->create();

        // Create some overdue tasks
        Task::factory()
            ->count(2)
            ->todo()
            ->overdue()
            ->create();
    }
}