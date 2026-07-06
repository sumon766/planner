@php
    $days = [
        'mon' => 'Mon',
        'tue' => 'Tue',
        'wed' => 'Wed',
        'thu' => 'Thu',
        'fri' => 'Fri',
        'sat' => 'Sat',
        'sun' => 'Sun',
    ];

    $dayColors = [
        'mon' => 'primary',
        'tue' => 'success',
        'wed' => 'warning',
        'thu' => 'info',
        'fri' => 'danger',
        'sat' => 'secondary',
        'sun' => 'dark',
    ];

    $weekdays = $task->weekdays ?? [];

    $hasSubtasks = $subtasks->isNotEmpty();

    $isTaskRunning = $runningTimer &&
        $runningTimer->routine_task_id == $task->id;

    $isChildRunning = $runningTimer &&
        $runningTimer->parent_task_id == $task->id;
@endphp

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 main-task-card">

    <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start gap-4">

            <div class="flex-grow-1">

                <div class="d-flex align-items-center gap-2 flex-wrap">

                    <h5 class="fw-bold mb-0">

                        {{ $task->title }}

                    </h5>

                    <span
                        class="badge rounded-pill
                        bg-{{ $task->is_active ? 'success' : 'secondary' }}-subtle
                        text-{{ $task->is_active ? 'success' : 'secondary' }}">

                        <i class="fa-solid {{ $task->is_active ? 'fa-circle-check' : 'fa-circle-pause' }} me-1"></i>

                        {{ $task->is_active ? 'Active' : 'Paused' }}

                    </span>

                </div>

                <p class="text-secondary small mt-3 mb-0">

                    {{ $task->description ?: 'Stay consistent by completing this routine regularly and breaking it down into smaller actionable subtasks.' }}

                </p>

            </div>

            <div class="text-end">

                @if($hasSubtasks)

                    @if($isChildRunning)

                        {{-- Live timer while a subtask is running --}}
                        <div
                            class="fw-semibold text-success"
                            wire:poll.1s="refreshRunningTimer">

                            <i class="fa-regular fa-clock me-1"></i>

                            {{ $this->getTimerDisplay($timerSeconds) }}

                        </div>

                        <small class="text-muted">

                            Working on {{ $runningTimer->task->title }}

                        </small>

                    @else

                        @php
                            $today = $this->getParentTodayTime($task->id);
                        @endphp

                        @if($today)

                            <div class="fw-semibold text-primary">

                                <i class="fa-regular fa-clock me-1"></i>

                                {{ $this->getTimerDisplay($today) }}

                            </div>

                            <small class="text-muted">

                                Today

                            </small>

                        @else

                            <small class="text-muted">

                                No work done today

                            </small>

                        @endif

                    @endif

                @else

                    @if($isTaskRunning)

                        <div
                            class="fw-semibold text-success mb-2"
                            wire:poll.1s="refreshRunningTimer">

                            <i class="fa-regular fa-clock me-1"></i>

                            {{ $this->getTimerDisplay($timerSeconds) }}

                        </div>

                        <button
                            wire:click="stopTimer"
                            class="btn btn-sm btn-outline-danger rounded-pill">

                            <i class="fa-solid fa-stop me-1"></i>

                            End Session

                        </button>

                    @else

                        @php
                            $today = $this->getParentTodayTime($task->id);
                        @endphp

                        @if($today)
                            <div class="d-flex">
                                <div class="fw-semibold text-primary" style="margin-right: 5px">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $this->formatDuration($today) }}
                                </div>
                                <p class="text-muted d-block mb-2">(Today)</p>
                            </div>
                        @endif

                        <button
                            wire:click="confirmStartTimer({{ $task->id }})"
                            class="btn btn-success btn-sm rounded-pill"
                            @disabled(!$task->is_active)>

                            <i class="fa-solid fa-play me-1"></i>

                            Start Session

                        </button>

                    @endif

                @endif

            </div>

        </div>

        {{-- Schedule --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

            <div class="d-flex flex-wrap gap-2">

                @forelse($weekdays as $day)

                    <span
                        class="badge rounded-pill
                        bg-{{ $dayColors[$day] }}-subtle
                        text-{{ $dayColors[$day] }}
                        px-3 py-2">

                        {{ $days[$day] }}

                    </span>

                @empty

                    <span class="text-muted small">

                        No schedule assigned

                    </span>

                @endforelse

            </div>

            <div class="btn-group">

                <a
                    href="{{ route('routine-tasks.edit', $task) }}"
                    class="btn btn-light btn-sm">

                    <i class="fa-solid fa-pen"></i>

                </a>

                <button
                    wire:click="toggleStatus({{ $task->id }})"
                    class="btn btn-light btn-sm">

                    <i class="fa-solid {{ $task->is_active ? 'fa-pause' : 'fa-play' }}"></i>

                </button>

                <button
                    wire:click="confirmDelete({{ $task->id }})"
                    class="btn btn-light btn-sm text-danger">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </div>

        </div>

        {{-- Subtasks --}}
        @if($hasSubtasks)

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h6 class="fw-semibold mb-0">

                        Subtasks

                    </h6>

                    <small class="text-muted">

                        {{ $subtasks->count() }}
                        {{ \Illuminate\Support\Str::plural('item', $subtasks->count()) }}

                    </small>

                </div>

                <a
                    href="{{ route('routine-tasks.create', ['parent' => $task->id]) }}"
                    class="btn btn-light btn-sm rounded-pill">

                    <i class="fa-solid fa-plus me-1"></i>

                    Add Subtask

                </a>

            </div>

            @foreach($subtasks as $subtask)

                @include('livewire.routine-tasks.partials.subtask-item', [
                    'subtask' => $subtask
                ])

            @endforeach
        @endif

    </div>

</div>
