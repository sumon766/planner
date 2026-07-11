<div>

    {{-- Categories --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Categories
            <span class="text-danger">*</span>
        </label>

        <select
            multiple
            class="form-select @error('categories') is-invalid @enderror"
            wire:model.live="categories">

            @foreach($categoryList as $category)

                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>

            @endforeach

        </select>

        <div class="form-text">
            Hold Ctrl (Windows) or Cmd (Mac) to select multiple categories.
        </div>

        @error('categories')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    {{-- Question --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Question
            <span class="text-danger">*</span>
        </label>

        <textarea
            rows="4"
            class="form-control @error('question') is-invalid @enderror"
            wire:model.live="question"
            placeholder="Enter the interview question..."></textarea>

        @error('question')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    {{-- Answer --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Answer
        </label>

        <livewire:jodit-text-editor
            wire:model.live="answer"
            wire:key="answer-editor" />

        @error('answer')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
        @enderror

    </div>

    <hr class="my-4">

    {{-- Information --}}
    <div class="alert alert-light border d-flex">

        <i class="fa-solid fa-lightbulb text-warning mt-1 me-3"></i>

        <div>

            <strong>Interview Tips</strong>

            <div class="small text-muted mt-1">

                Write concise questions and detailed answers.
                Use categories so the same question can appear in multiple interview topics.

            </div>

        </div>

    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-between mt-4">

        <a
            href="{{ route('interview-prep.index') }}"
            class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left me-2"></i>

            Cancel

        </a>

        <button
            class="btn btn-primary"
            wire:click="save"
            wire:loading.attr="disabled">

            <span wire:loading.remove>

                <i class="fa-solid fa-floppy-disk me-2"></i>

                Save Question

            </span>

            <span wire:loading>

                <span class="spinner-border spinner-border-sm me-2"></span>

                Saving...

            </span>

        </button>

    </div>

</div>
