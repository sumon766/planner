<div>

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
