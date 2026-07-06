<div>

    {{-- Title --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Task Title <span class="text-danger">*</span>
        </label>

        <input
            type="text"
            class="form-control @error('title') is-invalid @enderror"
            wire:model.live="title">

        @error('title')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    {{-- Description --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Description
        </label>

        <textarea
            rows="5"
            class="form-control @error('description') is-invalid @enderror"
            wire:model.live="description"></textarea>

        @error('description')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    {{-- Sort Order --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Sort Order
        </label>

        <input
            type="number"
            min="0"
            class="form-control @error('sort_order') is-invalid @enderror"
            wire:model.live="sort_order">

        <div class="form-text">
            Lower numbers appear first.
        </div>

        @error('sort_order')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    {{-- Status --}}
    <div class="mb-4">

        <label class="form-label fw-semibold">
            Status
        </label>

        <select
            class="form-select @error('status') is-invalid @enderror"
            wire:model.live="status">

            <option value="pending">
                Pending
            </option>

            <option value="completed">
                Completed
            </option>

            <option value="cancelled">
                Cancelled
            </option>

        </select>

        @error('status')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

    </div>

    <hr class="my-4">

    {{-- Buttons --}}
    <div class="d-flex justify-content-between">

        <a
            href="{{ route('extra-tasks.index') }}"
            class="btn btn-outline-secondary px-4">

            <i class="fa-solid fa-arrow-left me-2"></i>

            Cancel

        </a>

        <button
            class="btn btn-warning px-4"
            wire:click="update"
            wire:loading.attr="disabled">

            <span wire:loading.remove>

                <i class="fa-solid fa-floppy-disk me-2"></i>

                Update Task

            </span>

            <span wire:loading>

                <span class="spinner-border spinner-border-sm me-2"></span>

                Updating...

            </span>

        </button>

    </div>

</div>
