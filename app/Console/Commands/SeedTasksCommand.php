<?php

namespace App\Console\Commands;

use App\Enums\TaskStatusesEnum;
use App\Models\Task;
use App\Models\User;
use Faker\Factory;
use Illuminate\Console\Command;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\text;

class SeedTasksCommand extends Command
{
    protected $signature = 'seed:tasks';

    protected $description = 'Command description';

    public function handle(): void
    {
        $userId = text(
            label: 'Enter user ID for task or leave empty and user will be created: ',
            validate: ['numeric', 'exists:users,id'],
        );
        $userId && User::findOrFail($userId);
        $userId = $userId ?: \App\Models\User::factory()->create()->id;

        $faker = Factory::create();
        $targetCount = (int) text(
            label: 'Enter target count of tasks to create (default 2000): ',
            default: 2000,
            validate: [
                'numeric',
                'min:1',
            ]
        );
        $createdCount = 0;
        $statuses = TaskStatusesEnum::values();
        $parents = [];
        $progress = progress(label: 'Creating tasks', steps: $targetCount);
        $progress->start();

        $firstLvlCount = round($targetCount / 20);
        for ($i = 0; $i < $firstLvlCount; $i++) {

            $task = Task::create([
                'user_id' => $userId,
                'parent_task_id' => null,
                'status' => $statuses[array_rand($statuses)],
                'priority' => random_int(1, 5),
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'created_at' => now()->subDays(random_int(0, 30)),
                'completed_at' => null,
            ]);

            $parents[] = $task->id;
            $createdCount++;
            $progress->advance();
        }

        while ($createdCount < $targetCount && $parents !== []) {
            $newParents = [];

            foreach ($parents as $parentId) {
                $childrenCount = random_int(0, 5);
                for ($i = 0; $i < $childrenCount && $createdCount < $targetCount; $i++) {

                    $task = Task::create([
                        'user_id' => $userId,
                        'parent_task_id' => $parentId,
                        'status' => $statuses[array_rand($statuses)],
                        'priority' => random_int(1, 5),
                        'title' => $faker->sentence,
                        'description' => $faker->paragraph,
                        'created_at' => now()->subDays(random_int(0, 30)),
                        'completed_at' => random_int(0, 1) !== 0 ? now()->subDays(random_int(1, 10)) : null,
                    ]);

                    $newParents[] = $task->id;
                    $createdCount++;
                    $progress->advance();
                }
            }

            $parents = $newParents;
        }
    }
}
