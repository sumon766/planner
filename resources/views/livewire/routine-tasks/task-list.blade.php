<div class="container-xl py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">

        <div>

            <h2 class="fw-bold mb-1">
                Routine Tasks
            </h2>

            <p class="text-secondary mb-0">
                Organize your daily routines and track work sessions.
            </p>

        </div>

        <a
            href="{{ route('routine-tasks.create') }}"
            class="btn btn-primary rounded-pill px-4">

            <i class="fa-solid fa-plus me-2"></i>

            New Routine

        </a>

    </div>

    {{-- Running Session --}}
    @include('livewire.routine-tasks.partials.running-session')

    @if($tasks->isEmpty())

        @include('livewire.routine-tasks.partials.empty-state')

    @else

        @php
            $activeTasks = $tasks->where('is_active', true)->count();
            $subtaskCount = $tasks->sum(fn ($task) => ($subtasks[$task->id] ?? collect())->count());
        @endphp

        {{-- Task List --}}
        <div class="d-flex flex-column gap-4">

            @foreach($tasks as $task)

                @include(
                    'livewire.routine-tasks.partials.task-card',
                    [
                        'task' => $task,
                        'subtasks' => $subtasks[$task->id] ?? collect(),
                        'runningTimer' => $runningTimer,
                        'timerSeconds' => $timerSeconds,
                    ]
                )

            @endforeach

        </div>

    @endif

    @include('livewire.routine-tasks.partials.delete-modal')

</div>
