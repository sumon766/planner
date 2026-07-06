<?php

namespace App\Livewire\ExtraTasks;

use App\Models\ExtraTask;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskList extends Component
{
    public bool $showDeleteModal = false;

    public ?int $taskToDelete = null;

    public string $modalMessage = '';

    public function render()
    {
        $tasks = ExtraTask::query()
            ->where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.extra-tasks.task-list', [
            'tasks' => $tasks,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function markCompleted(int $taskId): void
    {
        $task = $this->findTask($taskId);

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
            'cancelled_at' => null,
        ]);

        flash()->success('Task marked as completed.');
    }

    public function markCancelled(int $taskId): void
    {
        $task = $this->findTask($taskId);

        $task->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'completed_at' => null,
        ]);

        flash()->warning('Task has been cancelled.');
    }

    public function markPending(int $taskId): void
    {
        $task = $this->findTask($taskId);

        $task->update([
            'status' => 'pending',
            'completed_at' => null,
            'cancelled_at' => null,
        ]);

        flash()->success('Task moved back to pending.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function confirmDelete(int $taskId): void
    {
        $task = $this->findTask($taskId);

        $this->taskToDelete = $task->id;

        $this->modalMessage = "Are you sure you want to delete '{$task->title}'?";

        $this->showDeleteModal = true;
    }

    public function deleteTask(): void
    {
        if (!$this->taskToDelete) {
            return;
        }

        $task = $this->findTask($this->taskToDelete);

        $task->delete();

        $this->reset([
            'showDeleteModal',
            'taskToDelete',
            'modalMessage',
        ]);

        flash()->success('Task deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function findTask(int $taskId): ExtraTask
    {
        return ExtraTask::query()
            ->where('user_id', Auth::id())
            ->findOrFail($taskId);
    }

    public function statusBadgeClass(string $status): string
    {
        return match ($status) {
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'warning',
        };
    }

    public function statusIcon(string $status): string
    {
        return match ($status) {
            'completed' => 'fa-circle-check',
            'cancelled' => 'fa-ban',
            default => 'fa-hourglass-half',
        };
    }

    public function canComplete(ExtraTask $task): bool
    {
        return $task->status !== 'completed';
    }

    public function canCancel(ExtraTask $task): bool
    {
        return $task->status !== 'cancelled';
    }

    public function canReset(ExtraTask $task): bool
    {
        return $task->status !== 'pending';
    }
}
