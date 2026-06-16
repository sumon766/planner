<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Title -->
    <div class="mb-3">
        <label class="form-label">Task Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title" placeholder="Enter task title">
        @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Parent -->
    <div class="mb-3">
        <label class="form-label">Parent Task (optional)</label>
        <select class="form-select" wire:model="parent_id">
            <option value="">None (Main Task)</option>
            @foreach($this->tasks as $task)
                <option value="{{ $task->id }}">{{ $task->title }}</option>
            @endforeach
        </select>
    </div>

    <!-- Description -->
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" wire:model="description" rows="5" placeholder="Optional description..."></textarea>
    </div>

    <!-- Active -->
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="is_active" wire:model="is_active">
        <label class="form-check-label" for="is_active">Active</label>
    </div>

    <!-- Save -->
    <button class="btn btn-dark" wire:click="save" wire:loading.attr="disabled">
        <span wire:loading.remove>Save Task</span>
        <span wire:loading>Saving...</span>
    </button>
</div>
