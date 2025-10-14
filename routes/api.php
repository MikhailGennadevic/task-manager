<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{task}', [TaskController::class, 'show']);
Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

// Task comments routes
Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->missing(function (Request $request) {
    return response()->json([
        'message' => 'Task not found'
    ], 404);
});