<?php

namespace App\Livewire\RoutineTasks;

use App\Models\RoutineTask;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateForm extends Component
{
    public string $title = '';
    public ?int $parent_id = null;
    public string $description = '';
    public bool $is_active = true;
    public array $weekdays = [];

    public function getTasksProperty()
    {
        return RoutineTask::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->get();
    }

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        RoutineTask::create([
            'user_id' => Auth::id(),
            'parent_id' => $this->parent_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'weekdays' => $this->weekdays,
        ]);

        $this->reset(['title', 'parent_id', 'description']);

        session()->flash('success', 'Task created successfully');
    }

    public function render()
    {
        return view('livewire.routine-tasks.create-form');
    }
}
