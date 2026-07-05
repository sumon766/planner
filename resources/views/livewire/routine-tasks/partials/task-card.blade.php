<div class="card border-0 shadow-sm h-100">

    {{-- Card Header --}}
    <div class="card-header bg-white border-bottom">

        <div class="d-flex justify-content-between align-items-start">

            <div>

                <h5 class="fw-bold mb-1">

                    {{ $task->title }}

                </h5>

                @if($task->description)

                    <small class="text-muted">

                        {{ $task->description }}

                    </small>

                @endif

            </div>

            <span class="badge bg-{{ $task->is_active ? 'success' : 'secondary' }}">

                {{ $task->is_active ? 'Active' : 'Inactive' }}

            </span>

        </div>

    </div>


    {{-- Card Body --}}
    <div class="card-body">

        {{-- Schedule --}}
        <div class="mb-4">

            <div class="small text-muted mb-2">

                Schedule

            </div>

            @php

                $days = [
                    'mon'=>'Mon',
                    'tue'=>'Tue',
                    'wed'=>'Wed',
                    'thu'=>'Thu',
                    'fri'=>'Fri',
                    'sat'=>'Sat',
                    'sun'=>'Sun',
                ];

                $weekdays = $task->weekdays ?? [];

            @endphp

            @if(count($weekdays))

                @foreach($weekdays as $day)

                    <span class="badge bg-light text-dark border me-1 mb-1">

                        {{ $days[$day] }}

                    </span>

                @endforeach

            @else

                <span class="text-muted">

                    No schedule assigned

                </span>

            @endif

        </div>


        {{-- Actions --}}
        <div class="d-flex flex-wrap gap-2 mb-4">

            @if($runningTimer && $runningTimer->routine_task_id == $task->id)

                <button
                    class="btn btn-danger btn-sm"
                    wire:click="stopTimer">

                    <i class="fa-solid fa-stop me-1"></i>

                    End Session

                </button>

            @else

                <button
                    class="btn btn-success btn-sm"
                    wire:click="startTimer({{ $task->id }})"
                    @disabled(!$task->is_active)>

                    <i class="fa-solid fa-play me-1"></i>

                    Start Session

                </button>

            @endif

            <a
                href="{{ route('routine-tasks.edit',$task) }}"
                class="btn btn-outline-primary btn-sm">

                <i class="fa-solid fa-pen me-1"></i>

                Edit

            </a>

            <button
                class="btn btn-outline-warning btn-sm"
                wire:click="toggleStatus({{ $task->id }})">

                <i class="fa-solid {{ $task->is_active ? 'fa-pause' : 'fa-play' }} me-1"></i>

                {{ $task->is_active ? 'Disable' : 'Enable' }}

            </button>

            <button
                class="btn btn-outline-danger btn-sm"
                wire:click="confirmDelete({{ $task->id }})">

                <i class="fa-solid fa-trash me-1"></i>

                Delete

            </button>

        </div>


        <hr>

        {{-- Subtasks --}}
        <div>

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="fw-semibold mb-0">

                    Subtasks

                </h6>

                <a
                    href="{{ route('routine-tasks.create',['parent'=>$task->id]) }}"
                    class="btn btn-sm btn-outline-success">

                    <i class="fa-solid fa-plus me-1"></i>

                    Add Subtask

                </a>

            </div>

            @if($subtasks->count())

                <div class="list-group list-group-flush">

                    @foreach($subtasks as $subtask)

                        @include(
                            'livewire.routine-tasks.partials.subtask-item',
                            [
                                'subtask'=>$subtask
                            ]
                        )

                    @endforeach

                </div>

            @else

                <div class="text-center py-4 text-muted">

                    <i class="fa-regular fa-folder-open fa-2x mb-3"></i>

                    <div>

                        No subtasks created.

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>
