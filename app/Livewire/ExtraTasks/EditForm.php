<?php

namespace App\Livewire\ExtraTasks;

use App\Models\ExtraTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditForm extends Component
{
    public ExtraTask $task;

    public string $title = '';

    public string $description = '';

    public string $status = 'pending';

    public int $sort_order = 0;

    /**
     * Load existing task data.
     */
    public function mount(ExtraTask $task): void
    {
        abort_unless($task->user_id === Auth::id(), 403);

        $this->task = $task;

        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->status = $task->status;
        $this->sort_order = $task->sort_order;
    }

    /**
     * Update task.
     */
    public function update()
    {
        $validated = $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'completed',
                    'cancelled',
                ]),
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        // Preserve completion/cancellation timestamps sensibly
        $completedAt = null;
        $cancelledAt = null;

        if ($validated['status'] === 'completed') {
            $completedAt = $this->task->completed_at ?: now();
        }

        if ($validated['status'] === 'cancelled') {
            $cancelledAt = $this->task->cancelled_at ?: now();
        }

        $this->task->update([
            'title' => trim($validated['title']),
            'description' => trim($validated['description'] ?? ''),
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'],
            'completed_at' => $completedAt,
            'cancelled_at' => $cancelledAt,
        ]);

        flash()->success('Extra task updated successfully.');

        return redirect()->route('extra-tasks.index');
    }

    public function render()
    {
        return view('livewire.extra-tasks.edit-form');
    }
}
