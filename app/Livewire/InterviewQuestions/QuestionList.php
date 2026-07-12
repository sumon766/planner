<?php

namespace App\Livewire\InterviewQuestions;

use App\Models\InterviewQuestion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuestionList extends Component
{
    public bool $showDeleteModal = false;

    public ?int $questionToDelete = null;

    public string $modalMessage = '';

    public string $search = '';

    public ?int $selectedCategory = null;

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function confirmDelete(int $id): void
    {
        $question = $this->findQuestion($id);

        $this->questionToDelete = $question->id;

        $this->modalMessage = "Are you sure you want to delete '{$question->question}'?";

        $this->showDeleteModal = true;
    }

    public function deleteQuestion(): void
    {
        if (!$this->questionToDelete) {
            return;
        }

        $question = $this->findQuestion($this->questionToDelete);

        // Remove pivot records first
        $question->categories()->detach();

        $question->delete();

        $this->reset([
            'showDeleteModal',
            'questionToDelete',
            'modalMessage',
        ]);

        flash()->success('Interview question deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function findQuestion(int $id): InterviewQuestion
    {
        return InterviewQuestion::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $questions = InterviewQuestion::query()
            ->with('categories')
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('question', 'like', "%{$this->search}%")
                        ->orWhere('answer', 'like', "%{$this->search}%")
                        ->orWhereHas('categories', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->selectedCategory, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->where('categories.id', $this->selectedCategory);
                });
            })
            ->latest()
            ->get();

        $categories = \App\Models\Category::query()
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.interview-questions.question-list', compact(
            'questions',
            'categories'
        ));
    }
}
