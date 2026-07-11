<div>

    {{-- Categories --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Categories
        </label>

        <div wire:ignore>

            <select
                id="categories"
                class="form-select"
                multiple>

                @foreach($categoryList as $category)

                    <option
                        value="{{ $category->id }}"
                        @selected(in_array((string)$category->id, $categories))>

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>

        </div>

        @error('categories')
        <div class="text-danger mt-2">{{ $message }}</div>
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
            class="form-control @error('questionText') is-invalid @enderror"
            wire:model.live="questionText"></textarea>

        @error('questionText')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    {{-- Answer --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Answer
        </label>

        <livewire:jodit-text-editor
            wire:model.live="answer"
            wire:key="edit-answer-editor" />

        @error('answer')
        <div class="text-danger mt-2">{{ $message }}</div>
        @enderror

    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between">

        <a
            href="{{ route('interview-prep.index') }}"
            class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left me-2"></i>

            Cancel

        </a>

        <button
            class="btn btn-warning"
            wire:click="update"
            wire:loading.attr="disabled">

            <span wire:loading.remove>

                <i class="fa-solid fa-floppy-disk me-2"></i>

                Update Question

            </span>

            <span wire:loading>

                <span class="spinner-border spinner-border-sm me-2"></span>

                Updating...

            </span>

        </button>

    </div>

</div>
