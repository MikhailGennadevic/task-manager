<?php

namespace App\Observers;

use App\Enums\NotificationType;
use App\Jobs\SendTaskNotificationJob;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        if ($task->priority === 'high') {
            SendTaskNotificationJob::dispatch($task, NotificationType::TASK_ASSIGNED);
        }
    }

    public function creating(Task $task)
    {
        if ($task->priority === 'high') {
            $task->status = 'in_progress';
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if (!$task->isDirty('status')) {
            return;
        }
            
        SendTaskNotificationJob::dispatch($task, NotificationType::STATUS_CHANGED);
     
        if (request()->has('user_id') && $task->status === 'completed') {
            Log::info('updated');
            $user = User::find(request('user_id'));
            $task->comments()->create([
                'user_id' => $user->id,
                'comment' => "Task completed by {$user->name}"
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }

    public function retrieved (Task $task): void
    {

    }
}
