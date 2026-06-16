<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Running Timer Banner -->
    @if($runningTimer)
        <div class="alert alert-primary shadow-sm mb-4" style="border-left: 4px solid #0d6efd;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="fa-solid fa-circle text-danger me-2" style="font-size: 10px;"></i> Running:</strong>
                    {{ $runningTimer->task->title }}
                    @if($runningTimer->task->parent_id)
                        <span class="text-muted">(subtask of {{ $runningTimer->task->parent->title ?? 'Parent' }})</span>
                    @endif
                    <span class="badge bg-dark ms-2" wire:poll.1s="refreshRunningTimer">
                        {{ $this->getTimerDisplay($timerSeconds) }}
                    </span>
                </div>
                <button class="btn btn-danger btn-sm" wire:click="stopTimer">
                    <i class="fa-solid fa-stop me-1"></i> Stop Timer
                </button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-semibold mb-1">Your Routine Tasks</h5>
            <p class="text-muted small mb-0">Track your daily routine with timers</p>
        </div>
        <div class="d-flex gap-2">
            @if($runningTimer)
                <button class="btn btn-outline-danger btn-sm" wire:click="stopAllTimers">
                    <i class="fa-solid fa-stop-circle me-1"></i> Stop All
                </button>
            @endif
            <a href="{{ route('routine-tasks.create') }}" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Add Task
            </a>
        </div>
    </div>

    <!-- Today's Summary -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body py-2">
                    <small class="text-muted">Today's Total</small>
                    <h5 class="mb-0">{{ $this->formatDuration($todayTotal) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body py-2">
                    <small class="text-muted">Active Tasks</small>
                    <h5 class="mb-0">{{ $tasks->where('is_active', true)->count() }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body py-2">
                    <small class="text-muted">Running Timer</small>
                    <h5 class="mb-0">{{ $runningTimer ? 'Yes' : 'No' }}</h5>
                </div>
            </div>
        </div>
    </div>

    @if($tasks->count() > 0)
        <div class="list-group">
            @foreach($tasks as $task)
                <div class="list-group-item border-0 shadow-sm mb-3 rounded-3 {{ $task->is_active ? '' : 'opacity-50' }}"
                     style="background: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-primary rounded-pill">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $task->target_minutes ?? 0 }} min
                                </span>
                                <div>
                                    <h6 class="mb-0 fw-semibold">
                                        @if(!$task->is_active)
                                            <span class="text-muted">[Inactive]</span>
                                        @endif
                                        {{ $task->title }}
                                    </h6>
                                    @if($task->description)
                                        <small class="text-muted">{{ $task->description }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @if($task->target_minutes)
                                <div class="mt-2" style="width: 200px;">
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-success"
                                             role="progressbar"
                                             style="width: {{ $this->getTimerProgress($task->id) }}%"
                                             aria-valuenow="{{ $this->getTimerProgress($task->id) }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted" style="font-size: 10px;">
                                        {{ $this->formatDuration($this->getTaskTimeToday($task->id)) }} / {{ $task->target_minutes }} min today
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <!-- Timer Button -->
                            @if($runningTimer && $runningTimer->routine_task_id == $task->id)
                                <button class="btn btn-danger btn-sm" wire:click="stopTimer">
                                    <i class="fa-solid fa-stop me-1"></i> Stop
                                </button>
                            @else
                                <button class="btn btn-success btn-sm"
                                        wire:click="confirmStartTimer({{ $task->id }})"
                                    {{ !$task->is_active ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-play me-1"></i> Start
                                </button>
                            @endif

                            <span class="badge bg-{{ $task->is_active ? 'success' : 'secondary' }}">
                                {{ $task->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            <div class="btn-group btn-group-sm">
                                <button wire:click="toggleStatus({{ $task->id }})"
                                        class="btn btn-outline-{{ $task->is_active ? 'warning' : 'success' }}"
                                        title="{{ $task->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fa-solid {{ $task->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $task->id }})"
                                        class="btn btn-outline-danger"
                                        title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Subtasks -->
                    @if(isset($subtasks[$task->id]) && $subtasks[$task->id]->count() > 0)
                        <div class="mt-3 ps-4 border-start border-2 border-primary">
                            @foreach($subtasks[$task->id] as $subtask)
                                <div class="d-flex justify-content-between align-items-center py-1 {{ !$subtask->is_active ? 'opacity-50' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-regular fa-circle text-secondary" style="font-size: 10px;"></i>
                                        <span class="small">{{ $subtask->title }}</span>
                                        @if($subtask->description)
                                            <small class="text-muted">- {{ $subtask->description }}</small>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Timer Button for Subtask -->
                                        @if($runningTimer && $runningTimer->routine_task_id == $subtask->id)
                                            <button class="btn btn-danger btn-sm" wire:click="stopTimer" style="padding: 2px 8px; font-size: 11px;">
                                                <i class="fa-solid fa-stop me-1"></i> Stop
                                            </button>
                                        @else
                                            <button class="btn btn-success btn-sm"
                                                    wire:click="confirmStartTimer({{ $subtask->id }})"
                                                    style="padding: 2px 8px; font-size: 11px;"
                                                {{ !$subtask->is_active ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-play me-1"></i> Start
                                            </button>
                                        @endif

                                        <span class="badge bg-{{ $subtask->is_active ? 'success' : 'secondary' }}" style="font-size: 10px;">
                                            {{ $subtask->is_active ? 'Active' : 'Inactive' }}
                                        </span>

                                        <div class="btn-group btn-group-sm">
                                            <button wire:click="toggleStatus({{ $subtask->id }})"
                                                    class="btn btn-outline-{{ $subtask->is_active ? 'warning' : 'success' }}"
                                                    style="padding: 2px 6px; font-size: 10px;">
                                                <i class="fa-solid {{ $subtask->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                            </button>
                                            <button wire:click="confirmDelete({{ $subtask->id }})"
                                                    class="btn btn-outline-danger"
                                                    style="padding: 2px 6px; font-size: 10px;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fa-regular fa-circle-check text-muted" style="font-size: 48px;"></i>
            <h6 class="mt-3">No tasks created yet</h6>
            <p class="text-muted small">Start by adding your first routine task</p>
            <a href="{{ route('routine-tasks.create') }}" class="btn btn-dark btn-sm">
                <i class="fa-solid fa-plus me-1"></i> Create Task
            </a>
        </div>
    @endif

    <!-- DELETE CONFIRMATION MODAL -->
    @if($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger fw-semibold">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            Confirm Deletion
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">{{ $modalMessage }}</p>
                        <p class="text-danger small">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button class="btn btn-danger" wire:click="deleteTask">
                            <i class="fa-solid fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TIMER CONFIRMATION MODAL -->
    @if($showTimerModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">
                            <i class="fa-solid fa-clock me-2 text-primary"></i>
                            Timer Confirmation
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showTimerModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">{{ $modalMessage }}</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button class="btn btn-secondary" wire:click="$set('showTimerModal', false)">
                            Cancel
                        </button>
                        <button class="btn btn-primary" wire:click="startTimer({{ $taskToTimer->id ?? 0 }})">
                            <i class="fa-solid fa-play me-1"></i> Start New
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
