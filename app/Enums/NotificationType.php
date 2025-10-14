<?php

namespace App\Enums;

use App\Models\Task;

enum NotificationType: string
{
    case STATUS_CHANGED = 'status_changed';
    case TASK_ASSIGNED = 'task_assigned';
    case OVERDUE = 'overdue';

    public function getMessage(Task $task): string
    {
        return match($this) {
            self::STATUS_CHANGED => "Task '{$task->title}' status changed to {$task->status}",
            self::TASK_ASSIGNED => "Task '{$task->title}' assigned to {$task->user->name}",
            self::OVERDUE => "Task is overdue! Created: {$task->created_at}",
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getDescription(): string
    {
        return match($this) {
            self::STATUS_CHANGED => 'Изменение статуса задачи',
            self::TASK_ASSIGNED => 'Назначение новой задачи',
            self::OVERDUE => 'Просроченная задача',
        };
    }
}