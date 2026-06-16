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
    public $showTimerModal = false;
    public $taskToDelete = null;
    public $taskToTimer = null;
    public $modalMessage = '';
    public $timerAction = '';
    public $activeTaskId = null;

    protected $listeners = ['refreshTimers' => 'refreshRunningTimer'];

    public function mount()
    {
        $this->refreshRunningTimer();
    }

    public function refreshRunningTimer()
    {
        $this->runningTimer = TaskTimeEntry::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->with('task')
            ->latest()
            ->first();

        if ($this->runningTimer) {
            $this->timerSeconds = $this->runningTimer->getDurationInSeconds();
            $this->activeTaskId = $this->runningTimer->routine_task_id;
        } else {
            $this->activeTaskId = null;
        }
    }

    public function render()
    {
        $tasks = RoutineTask::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $subtasks = RoutineTask::where('user_id', Auth::id())
            ->whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('parent_id');

        // Calculate today's total time
        $todayTotal = TaskTimeEntry::where('user_id', Auth::id())
            ->whereDate('started_at', today())
            ->sum('duration_seconds');

        // Get running timer info
        $runningTimer = $this->runningTimer;

        return view('livewire.routine-tasks.task-list', [
            'tasks' => $tasks,
            'subtasks' => $subtasks,
            'todayTotal' => $todayTotal,
            'runningTimer' => $runningTimer,
        ]);
    }

    public function confirmStartTimer($taskId)
    {
        $task = RoutineTask::where('user_id', Auth::id())->findOrFail($taskId);

        // If there's a running timer, ask to stop it first
        if ($this->runningTimer) {
            $this->taskToTimer = $task;
            $this->modalMessage = "You have a running timer for '{$this->runningTimer->task->title}'. Do you want to stop it and start '{$task->title}'?";
            $this->timerAction = 'start';
            $this->showTimerModal = true;
        } else {
            $this->startTimer($taskId);
        }
    }

    public function startTimer($taskId)
    {
        $task = RoutineTask::where('user_id', Auth::id())->findOrFail($taskId);

        // Stop any running timer first
        if ($this->runningTimer) {
            $this->stopTimer($this->runningTimer->id);
        }

        // Create new timer entry
        $entry = TaskTimeEntry::create([
            'user_id' => Auth::id(),
            'routine_task_id' => $task->id,
            'parent_task_id' => $task->parent_id ?? null,
            'started_at' => now(),
        ]);

        // If this is a subtask, ensure parent is tracked
        if ($task->parent_id) {
            $this->ensureParentTracking($task->parent_id, $entry);
        }

        $this->runningTimer = $entry;
        $this->timerSeconds = 0;
        $this->activeTaskId = $task->id;
        $this->showTimerModal = false;

        $this->dispatch('timer-started', taskId: $task->id);
        session()->flash('success', "Timer started for '{$task->title}'");
    }

    public function stopTimer($entryId = null)
    {
        if ($entryId) {
            $entry = TaskTimeEntry::where('user_id', Auth::id())
                ->where('id', $entryId)
                ->first();
        } else {
            $entry = TaskTimeEntry::where('user_id', Auth::id())
                ->whereNull('ended_at')
                ->latest()
                ->first();
        }

        if (!$entry) {
            return;
        }

        $entry->update([
            'ended_at' => now(),
            'duration_seconds' => now()->diffInSeconds($entry->started_at),
        ]);

        $this->runningTimer = null;
        $this->timerSeconds = 0;
        $this->activeTaskId = null;

        $this->dispatch('timer-stopped');
        session()->flash('success', 'Timer stopped');
    }

    public function stopAllTimers()
    {
        $entries = TaskTimeEntry::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->get();

        foreach ($entries as $entry) {
            $entry->update([
                'ended_at' => now(),
                'duration_seconds' => now()->diffInSeconds($entry->started_at),
            ]);
        }

        $this->runningTimer = null;
        $this->timerSeconds = 0;
        $this->activeTaskId = null;

        $this->dispatch('timer-stopped');
        session()->flash('success', 'All timers stopped');
    }

    private function ensureParentTracking($parentId, $childEntry)
    {
        // Check if parent has a running timer
        $parentTimer = TaskTimeEntry::where('user_id', Auth::id())
            ->where('routine_task_id', $parentId)
            ->whereNull('ended_at')
            ->first();

        // If parent doesn't have a timer, start one
        if (!$parentTimer) {
            TaskTimeEntry::create([
                'user_id' => Auth::id(),
                'routine_task_id' => $parentId,
                'parent_task_id' => null,
                'started_at' => $childEntry->started_at,
            ]);
        }
    }

    public function confirmDelete($taskId)
    {
        $this->taskToDelete = $taskId;
        $task = RoutineTask::where('user_id', Auth::id())->find($taskId);
        $hasChildren = RoutineTask::where('parent_id', $taskId)
            ->where('user_id', Auth::id())
            ->exists();

        $this->modalMessage = $hasChildren
            ? "This task has subtasks. Are you sure you want to delete this task and all its subtasks?"
            : "Are you sure you want to delete this task?";
        $this->showDeleteModal = true;
    }

    public function deleteTask()
    {
        if ($this->taskToDelete) {
            $task = RoutineTask::where('user_id', Auth::id())
                ->findOrFail($this->taskToDelete);

            // Stop timer if running for this task or its children
            $runningTimer = TaskTimeEntry::where('user_id', Auth::id())
                ->whereNull('ended_at')
                ->where('routine_task_id', $this->taskToDelete)
                ->orWhere('parent_task_id', $this->taskToDelete)
                ->first();

            if ($runningTimer) {
                $this->stopTimer($runningTimer->id);
            }

            // Delete subtasks if parent
            if ($task->parent_id === null) {
                RoutineTask::where('parent_id', $this->taskToDelete)
                    ->where('user_id', Auth::id())
                    ->delete();
            }

            $task->delete();

            $this->showDeleteModal = false;
            $this->taskToDelete = null;

            $this->dispatch('refreshTimers');
            session()->flash('success', 'Task deleted successfully');
        }
    }

    public function toggleStatus($taskId)
    {
        $task = RoutineTask::where('user_id', Auth::id())
            ->findOrFail($taskId);

        $task->is_active = !$task->is_active;
        $task->save();

        // If deactivating, stop any running timer
        if (!$task->is_active) {
            $runningTimer = TaskTimeEntry::where('user_id', Auth::id())
                ->whereNull('ended_at')
                ->where('routine_task_id', $taskId)
                ->orWhere('parent_task_id', $taskId)
                ->first();

            if ($runningTimer) {
                $this->stopTimer($runningTimer->id);
            }
        }

        $this->dispatch('refreshTimers');
        session()->flash('success', 'Task status updated');
    }

    public function getTaskTimeToday($taskId)
    {
        return TaskTimeEntry::where('user_id', Auth::id())
            ->where(function($query) use ($taskId) {
                $query->where('routine_task_id', $taskId)
                    ->orWhere('parent_task_id', $taskId);
            })
            ->whereDate('started_at', today())
            ->sum('duration_seconds');
    }

    public function getTaskTotalTime($taskId)
    {
        return TaskTimeEntry::where('user_id', Auth::id())
            ->where(function($query) use ($taskId) {
                $query->where('routine_task_id', $taskId)
                    ->orWhere('parent_task_id', $taskId);
            })
            ->sum('duration_seconds');
    }

    public function getTimerProgress($taskId)
    {
        $task = RoutineTask::where('user_id', Auth::id())->find($taskId);
        if (!$task || !$task->target_minutes) {
            return 0;
        }

        $totalSeconds = $this->getTaskTimeToday($taskId);
        $totalMinutes = $totalSeconds / 60;

        return min(($totalMinutes / $task->target_minutes) * 100, 100);
    }

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
        return sprintf('%dm %ds', $minutes, $secs);
    }
}
