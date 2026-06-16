<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_time_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('routine_task_id')
                ->constrained('routine_tasks')
                ->cascadeOnDelete();

            // if user starts subtask → parent is auto tracked
            $table->foreignId('parent_task_id')
                ->nullable()
                ->constrained('routine_tasks')
                ->nullOnDelete();

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            // optional cached duration (seconds)
            $table->integer('duration_seconds')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'routine_task_id']);
            $table->index(['started_at', 'ended_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_time_entries');
    }
};
