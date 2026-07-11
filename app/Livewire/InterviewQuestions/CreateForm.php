<?php

namespace App\Livewire\InterviewQuestions;

use App\Models\Category;
use App\Models\InterviewQuestion;
use Livewire\Component;

class CreateForm extends Component
{
    public string $question = '';

    public string $answer = '';

    public array $categories = [];

    /**
     * Save question
     */
    public function save()
    {
        $this->validate([
            'question' => [
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

        $question = InterviewQuestion::create([
            'question' => trim($this->question),
            'answer' => trim($this->answer),
        ]);

        $question->categories()->sync($this->categories);

        flash()->success('Interview question created successfully.');

        return redirect()->route('interview-prep.index');
    }

    public function render()
    {
        return view('livewire.interview-questions.create-form', [
            'categoryList' => Category::where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }
}
