<?php

namespace App\Console\Commands;

use App\Enums\NotificationType;

use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Console\Command;


class CheckOverdueTasks extends Command
{
    protected $signature = 'tasks:check-overdue 
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Check for overdue tasks and send notifications';

    public function handle(): void
    {
        $isDryRun = $this->option('dry-run');
        
        $overdueDate = now()->subDays(7);
        $overdueTasks = Task::where('status', 'in_progress')
            ->where('created_at', '<', $overdueDate)
            ->get();

        $this->info("Found {$overdueTasks->count()} overdue tasks (created before {$overdueDate->format('Y-m-d H:i:s')})");

        if (!$isDryRun) {
           return; 
        }


        foreach ($overdueTasks as $task) {
            $this->processOverdueTask($task);
        }
    }

    private function processOverdueTask(Task $task): void
    {
        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $task->user_id, // Используем ID назначенного пользователя
            'comment' => NotificationType::OVERDUE->getMessage($task),
        ]);

        SendTaskNotificationJob::dispatch($task, NotificationType::OVERDUE);
    }
}