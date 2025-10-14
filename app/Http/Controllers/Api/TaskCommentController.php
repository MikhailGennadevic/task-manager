<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskCommentRequest;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\JsonResponse;

class TaskCommentController extends Controller
{
    /**
     * POST /api/tasks/{id}/comments
     * Add comment to task
     */
    public function store(StoreTaskCommentRequest $request, Task $task): JsonResponse
    {
        $data = $request->validated();
        $data['task_id'] = $task->id;
        try{
            TaskComment::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
        return response()->json([
            'message' => 'Comment added successfully',
        ], 201);
    }
}