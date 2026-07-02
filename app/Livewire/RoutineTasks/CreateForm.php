<?php

namespace App\Livewire\RoutineTasks;

use App\Models\RoutineTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateForm extends Component
{
    public string $title = '';

    public string $taskType = 'main';

    public ?int $parent_id = null;

    public string $description = '';

    public bool $is_active = true;

    public array $weekdays = [];

    /**
     * Parent tasks for dropdown
     */
    public function getTasksProperty()
    {
        return RoutineTask::query()
            ->where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->active()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();
    }

    /**
     * Reset parent when switching back to Main Task
     */
    public function updatedTaskType($value): void
    {
        if ($value === 'main') {
            $this->parent_id = null;
        }
    }

    /**
     * Daily
     */
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

    /**
     * Sunday - Thursday (Weekdays)
     */
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

    /**
     * Friday - Saturday (Weekends)
     */
    public function selectWeekends(): void
    {
        $this->weekdays = [
            'fri',
            'sat',
        ];
    }

    /**
     * Clear selection
     */
    public function clearWeekdays(): void
    {
        $this->weekdays = [];
    }

    /**
     * Save task
     */
    public function save(): void
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

        RoutineTask::create([
            'user_id' => Auth::id(),

            'parent_id' => $this->taskType === 'sub'
                ? $this->parent_id
                : null,

            'title' => trim($this->title),

            'description' => trim($this->description),

            'is_active' => $this->is_active,

            'weekdays' => $this->weekdays,

            'sort_order' => 0,
        ]);

        $this->reset([
            'title',
            'parent_id',
            'description',
            'weekdays',
        ]);

        $this->taskType = 'main';
        $this->is_active = true;

        session()->flash('success', 'Routine task created successfully.');
    }

    public function render()
    {
        return view('livewire.routine-tasks.create-form');
    }
}
