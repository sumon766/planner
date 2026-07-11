<?php

namespace App\Livewire\InterviewQuestions;

use App\Models\Category;
use App\Models\InterviewQuestion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditForm extends Component
{
    public InterviewQuestion $question;

    public array $categories = [];

    public string $questionText = '';

    public string $answer = '';

    public function mount(InterviewQuestion $question): void
    {
        abort_unless($question->user_id === Auth::id(), 403);

        $this->question = $question;

        $this->questionText = $question->question;
        $this->answer = $question->answer ?? '';

        $this->categories = $question
            ->categories()
            ->pluck('categories.id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }

    public function update()
    {
        $validated = $this->validate([
            'questionText' => [
                'required',
                'string',
            ],

            'answer' => [
                'nullable',
                'string',
            ],

            'categories' => [
                'required',
                'array',
                'min:1',
            ],

            'categories.*' => [
                'exists:categories,id',
            ],
        ]);

        $this->question->update([
            'question' => trim($validated['questionText']),
            'answer'   => $validated['answer'],
        ]);

        $this->question->categories()->sync($validated['categories']);

        flash()->success('Interview question updated successfully.');

        return redirect()->route('interview-prep.index');
    }

    public function render()
    {
        return view('livewire.interview-questions.edit-form', [
            'categoryList' => Category::query()
                ->where('user_id', Auth::id())
                ->orderBy('name')
                ->get(),
        ]);
    }
}
