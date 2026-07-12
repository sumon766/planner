<?php

namespace App\Livewire\InterviewQuestions;

use App\Models\Category as InterviewCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Category extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Create Form
    |--------------------------------------------------------------------------
    */

    public string $name = '';

    public string $slug = '';

    public ?string $type = null;

    public ?string $description = null;

    public bool $is_active = true;

    /*
    |--------------------------------------------------------------------------
    | Edit Form
    |--------------------------------------------------------------------------
    */

    public ?int $editingId = null;

    public string $editingName = '';

    public string $editingSlug = '';

    public ?string $editingType = null;

    public ?string $editingDescription = null;

    public bool $editingIsActive = true;

    /*
    |--------------------------------------------------------------------------
    | Modals
    |--------------------------------------------------------------------------
    */

    public bool $showEditModal = false;

    public bool $showDeleteModal = false;

    public ?int $deleteId = null;

    public string $modalMessage = '';

    /*
    |--------------------------------------------------------------------------
    | Auto Generate Slug
    |--------------------------------------------------------------------------
    */

    public function updatedName($value): void
    {
        if (blank($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedEditingName($value): void
    {
        if (blank($this->editingSlug)) {
            $this->editingSlug = Str::slug($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')
                    ->where(fn ($query) => $query->where('user_id', Auth::id())),
            ],

            'type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'is_active' => [
                'boolean',
            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function save(): void
    {
        $validated = $this->validate();

        InterviewCategory::create([

            'user_id' => Auth::id(),

            'name' => trim($validated['name']),

            'slug' => Str::slug($validated['slug']),

            'type' => $validated['type'],

            'description' => $validated['description'],

            'is_active' => $validated['is_active'],

        ]);

        $this->reset([
            'name',
            'slug',
            'type',
            'description',
        ]);

        $this->is_active = true;

        flash()->success('Category created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(int $id): void
    {
        $category = $this->findCategory($id);

        $this->editingId = $category->id;

        $this->editingName = $category->name;

        $this->editingSlug = $category->slug;

        $this->editingType = $category->type;

        $this->editingDescription = $category->description;

        $this->editingIsActive = (bool) $category->is_active;

        $this->showEditModal = true;
    }

    public function update(): void
    {
        $this->validate([

            'editingName' => [
                'required',
                'string',
                'max:255',
            ],

            'editingSlug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')
                    ->where(fn ($query) => $query->where('user_id', Auth::id()))
                    ->ignore($this->editingId),
            ],

            'editingType' => [
                'nullable',
                'string',
                'max:255',
            ],

            'editingDescription' => [
                'nullable',
                'string',
            ],

            'editingIsActive' => [
                'boolean',
            ],

        ]);

        $category = $this->findCategory($this->editingId);

        $category->update([

            'name' => trim($this->editingName),

            'slug' => Str::slug($this->editingSlug),

            'type' => $this->editingType,

            'description' => $this->editingDescription,

            'is_active' => $this->editingIsActive,

        ]);

        $this->reset([

            'editingId',
            'editingName',
            'editingSlug',
            'editingType',
            'editingDescription',
            'editingIsActive',
            'showEditModal',

        ]);

        flash()->success('Category updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function confirmDelete(int $id): void
    {
        $category = $this->findCategory($id);

        $this->deleteId = $category->id;

        $this->modalMessage = "Are you sure you want to delete '{$category->name}'?";

        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        $category = $this->findCategory($this->deleteId);

        /*
         |--------------------------------------------------------------
         | If you have a belongsToMany relationship with interview
         | questions, uncomment the following line:
         |
         | $category->questions()->detach();
         |--------------------------------------------------------------
         */

        $category->delete();

        $this->reset([
            'deleteId',
            'showDeleteModal',
            'modalMessage',
        ]);

        flash()->success('Category deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected function findCategory(int $id): InterviewCategory
    {
        return InterviewCategory::query()
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
        return view('livewire.interview-questions.category', [

            'categories' => InterviewCategory::query()
                ->where('user_id', Auth::id())
                ->orderBy('name')
                ->get(),

        ]);
    }
}
