<?php

namespace App\Livewire\InterviewQuestions;

use App\Models\Category;
use App\Models\InterviewQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateForm extends Component
{
    public string $question = '';

    public string $answer = '';

    public array $categories = [];

    /**
     * Save question.
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
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query->where('user_id', Auth::id())),
            ],

        ]);

        $question = InterviewQuestion::create([

            'user_id' => Auth::id(),

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

            'categoryList' => Category::query()
                ->where('user_id', Auth::id())
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

        ]);
    }
}
