<?php

namespace App\Livewire\ExtraTasks;

use App\Models\ExtraTask;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateForm extends Component
{
    public string $title = '';

    public string $description = '';

    public int $sort_order = 0;

    /**
     * Save task
     */
    public function save()
    {
        $this->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        ExtraTask::create([
            'user_id' => Auth::id(),

            'title' => trim($this->title),

            'description' => trim($this->description),

            'status' => 'pending',

            'sort_order' => $this->sort_order,
        ]);

        flash()->success('Extra task created successfully.');

        return redirect()->route('extra-tasks.index');
    }

    public function render()
    {
        return view('livewire.extra-tasks.create-form');
    }
}
