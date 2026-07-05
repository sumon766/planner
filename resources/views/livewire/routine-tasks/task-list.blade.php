<div>

    {{-- Flash Message --}}
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            {{ session('success') }}

            <button
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Running Session --}}
    @include('livewire.routine-tasks.partials.running-session')


    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Routine Tasks
            </h2>

            <p class="text-muted mb-0">
                Organize your daily routines and track work sessions.
            </p>

        </div>

        <div>

            <a
                href="{{ route('routine-tasks.create') }}"
                class="btn btn-primary">

                <i class="fa-solid fa-plus me-2"></i>

                New Routine

            </a>

        </div>

    </div>


    @if($tasks->isEmpty())

        @include('livewire.routine-tasks.partials.empty-state')

    @else

        <div class="row g-4">

            @foreach($tasks as $task)

                <div class="col-lg-6">

                    @include(
                        'livewire.routine-tasks.partials.task-card',
                        [
                            'task' => $task,
                            'subtasks' => $subtasks[$task->id] ?? collect(),
                        ]
                    )

                </div>

            @endforeach

        </div>

    @endif


    @include('livewire.routine-tasks.partials.delete-modal')

</div>
