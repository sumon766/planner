<div>

    <div class="card-body">

        {{-- Title --}}
        <div class="mb-4">

            <label class="form-label fw-semibold">
                Task Title <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                class="form-control @error('title') is-invalid @enderror"
                wire:model.live="title"
                placeholder="Example: Renew Passport">

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
                wire:model.live="description"
                placeholder="Add any notes or details..."></textarea>

            @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

        </div>

        {{-- Sort Order --}}
        <div class="mb-4">

            <label
                for="sort_order"
                class="form-label fw-semibold">

                Sort Order

            </label>

            <input
                type="number"
                id="sort_order"
                min="0"
                step="1"
                class="form-control @error('sort_order') is-invalid @enderror"
                wire:model.live="sort_order"
                placeholder="0">

            <div class="form-text">
                Lower numbers appear first in the list.
            </div>

            @error('sort_order')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

        </div>

        <hr class="my-4">

        {{-- Information --}}
        <div class="alert alert-light border d-flex align-items-start">

            <i class="fa-solid fa-circle-info text-primary mt-1 me-3"></i>

            <div>

                <strong>New Extra Tasks</strong>

                <div class="small text-muted mt-1">
                    Every new task starts as
                    <span class="badge bg-warning-subtle text-warning">
                        Pending
                    </span>.
                    You can mark it as Completed or Cancelled later.
                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-between align-items-center mt-4">

            <a
                href="{{ route('extra-tasks.index') }}"
                class="btn btn-outline-secondary px-4">

                <i class="fa-solid fa-arrow-left me-2"></i>

                Cancel

            </a>

            <button
                class="btn btn-primary px-4"
                wire:click="save"
                wire:loading.attr="disabled">

                <span wire:loading.remove>

                    <i class="fa-solid fa-floppy-disk me-2"></i>

                    Save Extra Task

                </span>

                <span wire:loading>

                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"></span>

                    Saving...

                </span>

            </button>

        </div>

    </div>

</div>
