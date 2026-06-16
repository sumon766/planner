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
        Schema::create('routine_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // self-relation (parent task / subtask)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('routine_tasks')
                ->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // active/inactive toggle
            $table->boolean('is_active')->default(true);

            // optional scheduling
            $table->json('weekdays')->nullable();
            // example: ["mon","tue","wed","thu","fri"]

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index(['user_id', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_tasks');
    }
};
