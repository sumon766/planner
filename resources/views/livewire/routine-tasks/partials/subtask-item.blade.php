@php
    $isRunning = $runningTimer && $runningTimer->routine_task_id == $subtask->id;
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-3 subtask-card">

    <div class="card-body px-4 py-3">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start gap-3">

            <div class="flex-grow-1">

                <div class="d-flex align-items-center gap-2 mb-2">

                    <div class="text-primary">

                        <i class="fa-regular fa-circle-check fs-5"></i>

                    </div>

                    <h6 class="fw-semibold mb-0">

                        {{ $subtask->title }}

                    </h6>

                    @if(!$subtask->is_active)

                        <span class="badge rounded-pill bg-secondary-subtle text-secondary">

                            Paused

                        </span>

                    @endif

                </div>

                <p class="text-secondary small mb-0">

                    {{ $subtask->description ?: 'No description has been added for this subtask.' }}

                </p>

            </div>

            <div class="text-end">

                @if($isRunning)

                    <div
                        class="small fw-semibold text-success mb-2"
                        wire:poll.1s="refreshRunningTimer">

                        <i class="fa-regular fa-clock me-1"></i>

                        {{ $this->getTimerDisplay($timerSeconds) }}

                    </div>

                    <button
                        wire:click="stopTimer"
                        class="btn btn-sm btn-outline-danger rounded-pill px-3">

                        <i class="fa-solid fa-stop me-1"></i>

                        End Session

                    </button>

                @else

                    <button
                        wire:click="confirmStartTimer({{ $subtask->id }})"
                        class="btn btn-sm btn-success rounded-pill px-3"
                        @disabled(!$subtask->is_active)>

                        <i class="fa-solid fa-play me-1"></i>

                        Start Session

                    </button>

                @endif

            </div>

        </div>

        <hr class="my-3">

        {{-- Footer --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div class="d-flex align-items-center gap-3">

        <span
            class="badge rounded-pill
                bg-{{ $subtask->is_active ? 'success' : 'secondary' }}-subtle
                text-{{ $subtask->is_active ? 'success' : 'secondary' }}">

            <i class="fa-solid {{ $subtask->is_active ? 'fa-circle-check' : 'fa-circle-pause' }} me-1"></i>

            {{ $subtask->is_active ? 'Active' : 'Paused' }}

        </span>

                {{-- Today's Time --}}
                <small class="text-muted">

                    <i class="fa-regular fa-clock me-1"></i>

                    Today:

                    <strong>

                        @if($isRunning)

                            <span wire:poll.1s="refreshRunningTimer">

                        {{ $this->getTimerDisplay(
                            $this->getTaskTimeToday($subtask->id) + $timerSeconds
                        ) }}

                    </span>

                        @else

                            {{ $this->getTimerDisplay(
                                $this->getTaskTimeToday($subtask->id)
                            ) }}

                        @endif

                    </strong>

                </small>

            </div>

            <div class="btn-group">

                <a
                    href="{{ route('routine-tasks.edit', $subtask) }}"
                    class="btn btn-light btn-sm">

                    <i class="fa-solid fa-pen"></i>

                </a>

                <button
                    wire:click="toggleStatus({{ $subtask->id }})"
                    class="btn btn-light btn-sm">

                    <i class="fa-solid {{ $subtask->is_active ? 'fa-pause' : 'fa-play' }}"></i>

                </button>

                <button
                    wire:click="confirmDelete({{ $subtask->id }})"
                    class="btn btn-light btn-sm text-danger">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </div>

        </div>

    </div>

</div>
