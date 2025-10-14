<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskNotification;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $manager = User::create([
            'name' => 'Менеджер',
            'email' => 'manager@example.com',
            'position' => 'manager',
        ]);

        $developer = User::create([
            'name' => 'Разработчик',
            'email' => 'developer@example.com',
            'position' => 'developer',
        ]);

        $tester = User::create([
            'name' => 'Тестировщик',
            'email' => 'tester@example.com',
            'position' => 'tester',
        ]);

        $task = Task::create([
            'title' => 'Первая задача',
            'description' => 'Описание первой задачи',
            'user_id' => $developer->id,
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $manager->id,
            'comment' => 'Первый комментарий к задаче',
        ]);
    }
}