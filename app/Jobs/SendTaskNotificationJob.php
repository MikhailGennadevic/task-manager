<?php

namespace App\Jobs;

use App\Enums\NotificationType;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTaskNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;
    public $notificationType;

    public function __construct(Task $task, NotificationType $notificationType)
    {
        $this->task = $task;
        $this->notificationType = $notificationType;
    }

    public function handle(): void
    {
        $managers = User::where('position', 'manager')->get();

        if ($managers->isEmpty()) {
            Log::warning('No managers found for notification', [
                'task_id' => $this->task->id,
                'notification_type' => $this->notificationType->value
            ]);
            return;
        }

        $message = $this->notificationType->getMessage($this->task);

        foreach ($managers as $manager) {
            TaskNotification::create([
                'user_id' => $manager->id,
                'task_id' => $this->task->id,
                'message' => $message,
                'created_at' => now(),
            ]);

            Log::info('Task notification sent', [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'task_id' => $this->task->id,
                'task_title' => $this->task->title,
                'notification_type' => $this->notificationType->value,
                'message' => $message,
                'timestamp' => now()->toDateTimeString()
            ]);
        }

        Log::info('Task notifications processed', [
            'task_id' => $this->task->id,
            'notification_type' => $this->notificationType->value,
            'managers_count' => $managers->count(),
            'message' => $message
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Task notification job failed', [
            'task_id' => $this->task->id,
            'notification_type' => $this->notificationType->value,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}