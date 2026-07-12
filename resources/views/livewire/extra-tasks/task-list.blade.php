<div>

    @if($tasks->isEmpty())

        @include('livewire.extra-tasks.partials.empty-state')

    @else

        <div
            class="accordion"
            id="extraTasksAccordion">

            @foreach($tasks as $task)

                @php
                    $hasDescription = filled($task->description);
                @endphp

                <div class="accordion-item border rounded-4 shadow-sm mb-3 overflow-hidden">

                    <h2
                        class="accordion-header"
                        id="heading{{ $task->id }}">

                        <div
                            class="d-flex justify-content-between align-items-center px-3 py-3">

                            {{-- Left --}}
                            @if($hasDescription)

                                <button
                                    class="accordion-button collapsed shadow-none bg-white p-0 d-flex justify-content-between align-items-center"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $task->id }}"
                                    aria-expanded="false">

                                    <strong class="flex-grow-1">

                                        {{ $task->title }}

                                    </strong>

                                    <i class="fa-solid fa-chevron-down ms-3 accordion-icon"></i>

                                </button>

                            @else

                                <div class="fw-semibold">

                                    {{ $task->title }}

                                </div>

                            @endif

                            {{-- Right --}}
                            <div
                                class="d-flex align-items-center gap-2 ms-3">

                                @switch($task->status)

                                    @case('pending')

                                        <button
                                            wire:click="markCompleted({{ $task->id }})"
                                            class="btn btn-success btn-sm">

                                            Complete

                                        </button>

                                        <button
                                            wire:click="markCancelled({{ $task->id }})"
                                            class="btn btn-outline-danger btn-sm">

                                            Cancel

                                        </button>

                                        @break

                                    @case('completed')

                                        <span class="badge bg-success">

                                            Completed

                                        </span>

                                        @break

                                    @case('cancelled')

                                        <span class="badge bg-danger">

                                            Cancelled

                                        </span>

                                        @break

                                @endswitch

                                <a
                                    href="{{ route('extra-tasks.edit', $task) }}"
                                    class="btn btn-sm">

                                    <i class="fa-solid fa-pen"></i>

                                </a>

                                <button
                                    wire:click="confirmDelete({{ $task->id }})"
                                    class="btn btn-sm text-danger">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </div>

                        </div>

                    </h2>

                    @if($hasDescription)

                        <div
                            id="collapse{{ $task->id }}"
                            class="accordion-collapse collapse"
                            data-bs-parent="#extraTasksAccordion">

                            <div class="accordion-body">

                                {{ $task->description }}

                            </div>

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    @endif

    @include('livewire.extra-tasks.partials.delete-modal')
</div>
