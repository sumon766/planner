<?php

namespace App\Livewire\RoutineTasks;

use App\Models\RoutineTask;
use App\Models\TaskTimeEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskList extends Component
{
    public $runningTimer = null;
    public $timerSeconds = 0;

    public $showDeleteModal = false;
    public $taskToDelete = null;
    public $modalMessage = '';

    public $activeTaskId = null;
    public $taskToTimer = null;
    public $showTimerModal = false;

    protected $listeners = [
        'refreshTimers' => 'refreshRunningTimer',
    ];

    public function mount()
    {
        $this->refreshRunningTimer();
    }

    public function refreshRunningTimer()
    {
        $this->runningTimer = TaskTimeEntry::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->with(['task.parent'])
            ->latest()
            ->first();

        if ($this->runningTimer) {

            $this->timerSeconds = max(
                0,
                now()->timestamp - $this->runningTimer->started_at->timestamp
            );

            $this->activeTaskId = $this->runningTimer->routine_task_id;

        } else {

            $this->timerSeconds = 0;
            $this->activeTaskId = null;

        }
    }

    public function render()
    {
        $tasks = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $subtasks = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->whereNotNull('parent_id')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get()
            ->groupBy('parent_id');

        $todayTotal = TaskTimeEntry::query()
            ->where('user_id', Auth::id())
            ->whereDate('started_at', today())
            ->sum('duration_seconds');

        return view('livewire.routine-tasks.task-list', [
            'tasks' => $tasks,
            'subtasks' => $subtasks,
            'todayTotal' => $todayTotal,
            'runningTimer' => $this->runningTimer,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function hasSubtasks($task): bool
    {
        return RoutineTask::query()
            ->where('parent_id', $task->id)
            ->exists();
    }

    public function isTaskRunning($taskId): bool
    {
        return $this->runningTimer
            && $this->runningTimer->routine_task_id == $taskId;
    }

    public function isParentRunning($parentId): bool
    {
        return $this->runningTimer
            && $this->runningTimer->parent_task_id == $parentId;
    }

    /*
    |--------------------------------------------------------------------------
    | Timer Management
    |--------------------------------------------------------------------------
    */

    public function confirmStartTimer($taskId)
    {
        $task = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->findOrFail($taskId);

        // Parent tasks with subtasks cannot be started
        if (
            $task->parent_id === null &&
            RoutineTask::where('parent_id', $task->id)->exists()
        ) {
            flash()->error('Please start one of the subtasks instead.');
            return;
        }

        // Already running this task
        if (
            $this->runningTimer &&
            $this->runningTimer->routine_task_id == $task->id
        ) {
            return;
        }

        // Ask before switching
        if ($this->runningTimer) {

            $this->taskToTimer = $task;

            $this->modalMessage =
                "A work session is already running for '{$this->runningTimer->task->title}'. "
                ."Do you want to stop it and start '{$task->title}'?";

            $this->showTimerModal = true;

            return;
        }

        $this->startTimer($task->id);
    }

    public function switchTimer()
    {
        if (!$this->taskToTimer) {
            return;
        }

        $this->stopTimer();

        $this->startTimer($this->taskToTimer->id);

        $this->showTimerModal = false;
        $this->taskToTimer = null;
    }

    public function startTimer($taskId)
    {
        $task = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->findOrFail($taskId);

        // Parent task with subtasks cannot start
        if (
            $task->parent_id === null &&
            RoutineTask::where('parent_id', $task->id)->exists()
        ) {
            flash()->error('Please start one of the subtasks instead.');
            return;
        }

        // Safety: never allow two running timers
        if ($this->runningTimer) {
            flash()->warning('Another work session is already running.');
            return;
        }

        $entry = TaskTimeEntry::create([
            'user_id'         => Auth::id(),
            'routine_task_id' => $task->id,
            'parent_task_id'  => $task->parent_id,
            'started_at'      => now(),
        ]);

        $this->runningTimer = $entry->load('task');
        $this->timerSeconds = 0;

        $this->dispatch('timer-started');

        flash()->success("Started '{$task->title}'.");
    }

    public function stopTimer()
    {
        if (!$this->runningTimer) {
            return;
        }

        $endedAt = now();

        $duration = max(
            0,
            $this->runningTimer->started_at->diffInSeconds($endedAt)
        );

        $this->runningTimer->update([
            'ended_at' => $endedAt,
            'duration_seconds' => $duration,
        ]);

        $this->runningTimer = null;
        $this->timerSeconds = 0;

        $this->dispatch('timer-stopped');

        flash()->warning('Session ended.');
    }

    /*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

    public function confirmDelete($taskId)
    {
        $this->taskToDelete = $taskId;

        $task = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->findOrFail($taskId);

        $hasChildren = RoutineTask::query()
            ->where('parent_id', $taskId)
            ->exists();

        $this->modalMessage = $hasChildren
            ? "This routine contains subtasks. Deleting it will remove all subtasks as well. Continue?"
            : "Are you sure you want to delete this task?";

        $this->showDeleteModal = true;
    }

    public function deleteTask()
    {
        if (!$this->taskToDelete) {
            return;
        }

        $task = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->findOrFail($this->taskToDelete);

        /*
        |--------------------------------------------------------------------------
        | Stop running timer if necessary
        |--------------------------------------------------------------------------
        */

        if (
            $this->runningTimer &&
            (
                $this->runningTimer->routine_task_id == $task->id ||
                $this->runningTimer->parent_task_id == $task->id
            )
        ) {
            $this->stopTimer();
        }

        /*
        |--------------------------------------------------------------------------
        | Delete subtasks
        |--------------------------------------------------------------------------
        */

        if ($task->parent_id === null) {

            RoutineTask::query()
                ->where('parent_id', $task->id)
                ->delete();
        }

        $task->delete();

        $this->showDeleteModal = false;
        $this->taskToDelete = null;

        $this->refreshRunningTimer();

        flash()->success('Task deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public function toggleStatus($taskId)
    {
        $task = RoutineTask::query()
            ->where('user_id', Auth::id())
            ->findOrFail($taskId);

        $task->update([
            'is_active' => !$task->is_active,
        ]);

        if (
            !$task->is_active &&
            $this->runningTimer &&
            (
                $this->runningTimer->routine_task_id == $task->id ||
                $this->runningTimer->parent_task_id == $task->id
            )
        ) {
            $this->stopTimer();
        }

        flash()->success('Task updated.');
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics Helpers
    |--------------------------------------------------------------------------
    */

    public function getTaskTimeToday($taskId)
    {
        return TaskTimeEntry::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($taskId) {

                $query->where('routine_task_id', $taskId)
                    ->orWhere('parent_task_id', $taskId);

            })
            ->whereDate('started_at', today())
            ->sum('duration_seconds');
    }

    public function getTaskTotalTime($taskId)
    {
        return TaskTimeEntry::query()
            ->where('user_id', Auth::id())
            ->where(function ($query) use ($taskId) {

                $query->where('routine_task_id', $taskId)
                    ->orWhere('parent_task_id', $taskId);

            })
            ->sum('duration_seconds');
    }

    public function getTimerProgress($taskId)
    {
        $task = RoutineTask::find($taskId);

        if (!$task || !$task->target_minutes) {
            return 0;
        }

        $minutes = $this->getTaskTimeToday($taskId) / 60;

        return min(
            ($minutes / $task->target_minutes) * 100,
            100
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formatting
    |--------------------------------------------------------------------------
    */

    public function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%02d:%02d', $minutes, $secs);
    }

    public function getTimerDisplay($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %02dm %02ds', $hours, $minutes, $secs);
        }

        return sprintf('%dm %02ds', $minutes, $secs);
    }

    public function getParentTodayTime($parentTaskId): int
    {
        return TaskTimeEntry::query()
            ->where('user_id', Auth::id())
            ->whereDate('started_at', today())
            ->where(function ($query) use ($parentTaskId) {
                $query->where('routine_task_id', $parentTaskId)
                    ->orWhere('parent_task_id', $parentTaskId);
            })
            ->sum('duration_seconds');
    }

    public function getSubtaskTodayTime($subtaskId): int
    {
        return TaskTimeEntry::query()
            ->where('user_id', Auth::id())
            ->whereDate('started_at', today())
            ->where('routine_task_id', $subtaskId)
            ->sum('duration_seconds');
    }

    public function getLiveTaskSeconds($taskId): int
    {
        if (!$this->runningTimer) {
            return 0;
        }

        if ($this->runningTimer->routine_task_id == $taskId) {
            return $this->timerSeconds;
        }

        return 0;
    }
}
