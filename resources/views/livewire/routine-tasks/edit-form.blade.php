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
                placeholder="Example: Job Search">

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

            <livewire:jodit-text-editor
                wire:model.live="description"
                wire:key="edit-description-editor-{{ $this->id ?? 'new' }}"
            />
        </div>

        {{-- Task Type --}}
        <div class="mb-4">

            <label class="form-label fw-semibold">
                Task Type
            </label>

            <div class="form-check">

                <input
                    class="form-check-input"
                    type="radio"
                    id="mainTask"
                    value="main"
                    wire:model.live="taskType">

                <label class="form-check-label" for="mainTask">
                    Main Task
                </label>

            </div>

            <div class="form-check">

                <input
                    class="form-check-input"
                    type="radio"
                    id="subTask"
                    value="sub"
                    wire:model.live="taskType">

                <label class="form-check-label" for="subTask">
                    Sub Task
                </label>

            </div>

        </div>


        {{-- Parent Task --}}
        @if($taskType === 'sub')

            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Parent Task
                </label>

                <select
                    class="form-select @error('parent_id') is-invalid @enderror"
                    wire:model="parent_id">

                    <option value="">
                        Select Parent Task
                    </option>

                    @forelse($this->tasks as $task)

                        <option value="{{ $task->id }}">
                            {{ $task->title }}
                        </option>

                    @empty

                        <option disabled>
                            No parent task available
                        </option>

                    @endforelse

                </select>

                @error('parent_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror

            </div>

        @endif

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

                Lower numbers appear first in the task list.

            </div>

            @error('sort_order')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

            @enderror

        </div>

        {{-- Weekdays --}}
        <div class="mb-3">

            <label class="form-label fw-semibold">
                Run On
            </label>

            <div class="row">

                @php
                    $days = [
                        'sun' => 'Sunday',
                        'mon' => 'Monday',
                        'tue' => 'Tuesday',
                        'wed' => 'Wednesday',
                        'thu' => 'Thursday',
                        'fri' => 'Friday',
                        'sat' => 'Saturday',
                    ];
                @endphp

                @foreach($days as $key => $label)

                    <div class="col-md-3 mb-2">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="{{ $key }}"
                                wire:model="weekdays"
                                id="day_{{ $key }}">

                            <label
                                class="form-check-label"
                                for="day_{{ $key }}">

                                {{ $label }}

                            </label>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- Quick Buttons --}}
        <div class="mb-4">

            <label class="form-label fw-semibold">
                Quick Select
            </label>

            <div class="d-flex flex-wrap gap-2">

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm"
                    wire:click="selectDaily">

                    Daily

                </button>

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm"
                    wire:click="selectWeekdays">

                    Weekdays

                </button>

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm"
                    wire:click="selectWeekends">

                    Weekends

                </button>

                <button
                    type="button"
                    class="btn btn-outline-secondary btn-sm"
                    wire:click="clearWeekdays">

                    Clear

                </button>

            </div>

        </div>


        <hr class="my-4">


        {{-- Active --}}
        <div class="form-check form-switch mb-4">

            <input
                class="form-check-input"
                type="checkbox"
                id="is_active"
                wire:model="is_active">

            <label
                class="form-check-label"
                for="is_active">

                Active Task

            </label>

        </div>


        {{-- Submit & Cancel --}}
        <div class="d-flex justify-content-between align-items-center">

            <a
                href="{{ route('routine-tasks.index') }}"
                class="btn btn-outline-secondary px-4">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Cancel
            </a>

            <button
                class="btn btn-primary px-4"
                wire:click="save"
                wire:loading.attr="disabled">

                    <span wire:loading.remove>
                        <i class="fa-solid fa-check me-2"></i>
                        Update Task
                    </span>

                <span wire:loading>
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Updating...
                    </span>

            </button>

        </div>

    </div>

</div>
