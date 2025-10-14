<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Models\User;
use App\Jobs\SendTaskStatusNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;


class TaskController extends Controller
{
    /**
     * GET /api/tasks
     * Get all tasks
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = Task::with('user:id,name,position')
            ->status($request->status)
            ->priority($request->priority)
            ->user($request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $tasks,
        ]);
    }

    /**
     * POST /api/tasks
     * Create new task
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!isset($data['user_id'])) {
            $manager = User::where('position', 'manager')->first();
            
            if (!$manager) {
                return response()->json([
                    'error' => 'No manager found to assign the task'
                ], 422);
            }
            
            $data['user_id'] = $manager->id;
        }

        $task = Task::create($data);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task->load('user:id,name,position')
        ], 201);
    }

    /**
     * PUT /api/tasks/{id}/status
     * Change task status
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {        
        $data = $request->validated();
     
        $task->update(['status' => $data['status']]);
        
        return response()->json([
            'message' => 'Task status updated successfully',
        ]);
    }

    /**
     * GET /api/tasks/{id}
     * Получить задачу с комментариями и информацией о пользователе
     */
    public function show(Task $task): JsonResponse
    {
        if (!$task->comments) {
            $task->setRelation('comments', collect());
        }

        return response()->json([
            'data' => $task
        ]);
    }
}