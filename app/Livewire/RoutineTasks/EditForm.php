<?php

namespace App\Livewire\RoutineTasks;

use App\Models\RoutineTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditForm extends Component
{
    public RoutineTask $task;

    public string $title = '';

    public string $taskType = 'main';

    public ?int $parent_id = null;

    public string $description = '';

    public bool $is_active = true;

    public array $weekdays = [];

    public int $sort_order = 0;

    /**
     * Load task into the form.
     */
    public function mount(RoutineTask $task): void
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $this->task = $task;

        $this->title = $task->title;

        $this->taskType = $task->parent_id ? 'sub' : 'main';

        $this->parent_id = $task->parent_id;

        $this->description = $task->description ?? '';

        $this->is_active = $task->is_active;

        $this->weekdays = $task->weekdays ?? [];

        $this->sort_order = $task->sort_order;
    }

    /**
     * Parent tasks for dropdown.
     */
    public function getTasksProperty()
    {
        return RoutineTask::query()
            ->where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->whereKeyNot($this->task->id)
            ->active()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    /**
     * Reset parent when switching back to Main Task.
     */
    public function updatedTaskType($value): void
    {
        if ($value === 'main') {
            $this->parent_id = null;
        }
    }

    public function selectDaily(): void
    {
        $this->weekdays = [
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
            'fri',
            'sat',
        ];
    }

    public function selectWeekdays(): void
    {
        $this->weekdays = [
            'sun',
            'mon',
            'tue',
            'wed',
            'thu',
        ];
    }

    public function selectWeekends(): void
    {
        $this->weekdays = [
            'fri',
            'sat',
        ];
    }

    public function clearWeekdays(): void
    {
        $this->weekdays = [];
    }

    /**
     * Update task.
     */
    public function save()
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],

            'taskType' => [
                'required',
                Rule::in(['main', 'sub']),
            ],

            'parent_id' => [
                Rule::requiredIf($this->taskType === 'sub'),
                'nullable',
                'exists:routine_tasks,id',
            ],

            'description' => ['nullable', 'string'],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'weekdays' => ['array'],

            'weekdays.*' => [
                Rule::in([
                    'mon',
                    'tue',
                    'wed',
                    'thu',
                    'fri',
                    'sat',
                    'sun',
                ]),
            ],
        ]);

        $this->task->update([
            'parent_id' => $this->taskType === 'sub'
                ? $this->parent_id
                : null,

            'title' => trim($this->title),

            'description' => trim($this->description),

            'is_active' => $this->is_active,

            'weekdays' => $this->weekdays,

            'sort_order' => $this->sort_order,
        ]);

        flash()->success('Routine task updated successfully.');

        return redirect()->route('routine-tasks.index');
    }

    public function render()
    {
        return view('livewire.routine-tasks.edit-form');
    }
}
