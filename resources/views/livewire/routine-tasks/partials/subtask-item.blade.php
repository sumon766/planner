<div class="list-group-item px-0 py-3 border-bottom">

    <div class="d-flex justify-content-between align-items-center">

        {{-- Left --}}
        <div>

            <div class="fw-semibold">

                {{ $subtask->title }}

            </div>

            @if($subtask->description)

                <small class="text-muted">

                    {{ $subtask->description }}

                </small>

            @endif

            <div class="mt-2">

                <span class="badge bg-{{ $subtask->is_active ? 'success' : 'secondary' }}">

                    {{ $subtask->is_active ? 'Active' : 'Inactive' }}

                </span>

            </div>

        </div>


        {{-- Right --}}
        <div class="text-end">

            {{-- Timer --}}
            @if($runningTimer && $runningTimer->routine_task_id == $subtask->id)

                <button
                    class="btn btn-danger btn-sm"
                    wire:click="stopTimer">

                    <i class="fa-solid fa-stop me-1"></i>

                    End Session

                </button>

            @else

                <button
                    class="btn btn-success btn-sm"
                    wire:click="startTimer({{ $subtask->id }})"
                    @disabled(!$subtask->is_active)>

                    <i class="fa-solid fa-play me-1"></i>

                    Start Session

                </button>

            @endif

            <div class="btn-group ms-2">

                <a
                    href="{{ route('routine-tasks.edit',$subtask) }}"
                    class="btn btn-outline-primary btn-sm">

                    <i class="fa-solid fa-pen"></i>

                </a>

                <button
                    class="btn btn-outline-warning btn-sm"
                    wire:click="toggleStatus({{ $subtask->id }})">

                    <i class="fa-solid {{ $subtask->is_active ? 'fa-pause' : 'fa-play' }}"></i>

                </button>

                <button
                    class="btn btn-outline-danger btn-sm"
                    wire:click="confirmDelete({{ $subtask->id }})">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </div>

        </div>

    </div>

</div>
