<?php

namespace App\Models;

use App\Enums\TaskStatusesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    public const UPDATED_AT = null;
    protected $fillable = [
        'user_id',
        'status',
        'priority',
        'title',
        'description',
        'completed_at',
        'parent_task_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return (bool)$this->completed_at;
    }
}
