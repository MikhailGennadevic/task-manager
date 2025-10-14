<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'priority',
    ];

    protected $attributes = [
        'status' => 'new',
        'priority' => 'normal',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function notifications()
    {
        return $this->hasMany(TaskNotification::class);
    }


    public function scopeStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopePriority($query, $priority)
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    public function scopeUser($query, $userId)
    {
        return $userId ? $query->where('user_id', $userId) : $query;
    }
}