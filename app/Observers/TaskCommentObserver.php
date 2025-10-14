<?php

namespace App\Observers;

use App\Models\TaskComment;
use Illuminate\Validation\ValidationException;

class TaskCommentObserver
{
    public function creating(TaskComment $taskComment): mixed
    {
        if ($taskComment->task->status === 'cancelled') {
            throw ValidationException::withMessages([
                'priority' => ['Cancelled tasks cannot be created at this time']
            ]);
        }
        return true;
    }
    /**
     * Handle the TaskComment "created" event.
     */
    public function created(TaskComment $taskComment): void
    {
        
    }

    /**
     * Handle the TaskComment "updated" event.
     */
    public function updated(TaskComment $taskComment): void
    {
        //
    }

    /**
     * Handle the TaskComment "deleted" event.
     */
    public function deleted(TaskComment $taskComment): void
    {
        //
    }

    /**
     * Handle the TaskComment "restored" event.
     */
    public function restored(TaskComment $taskComment): void
    {
        //
    }

    /**
     * Handle the TaskComment "force deleted" event.
     */
    public function forceDeleted(TaskComment $taskComment): void
    {
        //
    }
}
