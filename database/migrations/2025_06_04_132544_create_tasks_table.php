<?php

use App\Enums\TaskStatusesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', [TaskStatusesEnum::TODO->value, TaskStatusesEnum::DONE->value])->index();
            $table->tinyInteger('priority')->index();
            $table->string('title')->fulltext();
            $table->text('description')->fulltext();
            $table->timestamp('created_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
